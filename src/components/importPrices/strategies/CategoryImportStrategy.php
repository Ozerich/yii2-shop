<?php
namespace ozerich\shop\components\importPrices\strategies;


use moonland\phpexcel\Excel;
use ozerich\shop\components\exportPrices\strategies\CategoryExportStrategy;
use ozerich\shop\components\importPrices\ImportPricesStrategyInterface;
use ozerich\shop\constants\DiscountType;
use ozerich\shop\constants\Stock;
use ozerich\shop\models\Category;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductPrice;
use ozerich\shop\models\ProductPriceParam;
use ozerich\shop\models\ProductPriceParamValue;
use ozerich\shop\traits\ServicesTrait;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class CategoryImportStrategy implements ImportPricesStrategyInterface
{
    use ServicesTrait;

    private $offset = 0;
    private $price_param;
    private $price_param_second;

    private $price_param_id;
    private $price_param_second_id;

    private $titles;

    private $_file;
    private $lines = 0;
    private $products = [];

    private $categories = [];

    // columns //
    const ID = 'A';
    const ID_PRICE = 'S';
    const VALID = 'T';
    const PRICE = 'K';
    const PRICE_WITH_PROMO = 'L';
    const PERCENT = 'M';
    const PROMO_AMOUNT = 'N';
    const STOCK = 'P';
    const STOCK_DAYS = 'Q';

    const PARAM_1 = 'E';
    const PARAM_2 = 'F';
    const PARAM_3 = 'G';
    const PARAM_4 = 'H';
    const PARAM_5 = 'I';
    const PARAM_6 = 'J';

    public function init($file){
        ini_set('max_execution_time', 100);
        $this->_file = $file;
    }

    public function import(){
        try {
            $data = Excel::import($this->_file['tmp_name'], [
                'setFirstRecordAsKeys' => false
            ]);
        } catch (Exception $exception){
            return false;
        }
        if($data && is_array($data)){
            foreach ($data as $key => $sheet) {
                if($sheet && is_array($sheet)) {
                    if($sheet[self::ID] !== 'ID') {
                        $this->getCategoryParams($this->titles, $sheet);
                        $result = $this->load($sheet);
                        if(!$result) return false;
                    } else {
                        $this->titles = $sheet;
                    }
                }
            }
            $this->afterSave();
            return "Импортировано ". count($this->products) . " товаров (" . $this->lines . " позиций)";
        }
        return false;
    }

    private function load($row){
        if(!$this->validateRow($row)) return false;
        $product = Product::findOne($row[self::ID]);
        if($product) {
            if($product->category) {
                $this->categories[$product->category_id] = true;
            }
            $this->lines++;
            $this->products[$product->id] = true;
            if($row[$this->offsetLeter(self::ID_PRICE)]) {
                $productPrice = ProductPrice::findOne($row[$this->offsetLeter(self::ID_PRICE)]);
                if($productPrice) {
                    $result = $this->updateModel($productPrice, $row);
                    if($result) {
                        if($product->is_prices_extended) {
                            $this->updatePrices($product->id);
                        }
                    }
                    return $result;
                } else {
                    $result = $this->updateModelByParamsNames($row);
                    if($result) {
                        if($product->is_prices_extended) {
                            $this->updatePrices($product->id);
                        }
                    }
                    return $result;
                }
            } else {
                $result = $this->updateModel($product, $row);
                if($result) {
                    if($product->is_prices_extended) {
                        $this->updatePrices($product->id);
                    }
                }
                return $result;
            }
        } elseif(!$row[$this->offsetLeter(self::ID)]) return true;
        return true;
    }


    private function afterSave() {
        foreach ($this->categories as $key => $v) {
            $this->categoryProductsService()->updateCategoryStats(Category::findOne($key));
        }
    }

    private function validateRow($row){
        if($row[$this->offsetLeter(self::ID)] && !$row[$this->offsetLeter(self::ID_PRICE)]){
            return true;
        }
        elseif(($row[$this->offsetLeter(self::ID)] * ($row[$this->offsetLeter(self::ID_PRICE)] + 3)) == $row[$this->offsetLeter(self::VALID)]) {
            return true;
        }
        return false;
    }

    private function updateModel($model, $row){
        if($row[$this->offsetLeter(self::PRICE_WITH_PROMO)]){
            $model->discount_mode = DiscountType::FIXED;
            $model->discount_value = $row[$this->offsetLeter(self::PRICE_WITH_PROMO)];
        } elseif($row[$this->offsetLeter(self::PERCENT)]) {
            $model->discount_mode = DiscountType::PERCENT;
            $model->discount_value = $row[$this->offsetLeter(self::PERCENT)];
        } elseif($row[$this->offsetLeter(self::PROMO_AMOUNT)]) {
            $model->discount_mode = DiscountType::AMOUNT;
            $model->discount_value = $row[$this->offsetLeter(self::PROMO_AMOUNT)];
        } else {
            $model->discount_mode = null;
            $model->discount_value = null;
        }

        if($model INSTANCEOF Product){
            $model->price = $row[$this->offsetLeter(self::PRICE)] ? $row[$this->offsetLeter(self::PRICE)] : null;
            $model->price_with_discount = $this->getPriceWithDiscount(
                $row[$this->offsetLeter(self::PRICE)],
                $model->discount_value,
                $model->discount_mode
            );
        } else {
            $model->value = $row[$this->offsetLeter(self::PRICE)];
        }
        $model->stock = Stock::toValue($row[$this->offsetLeter(self::STOCK)]);
        $model->stock_waiting_days = (int)$row[$this->offsetLeter(self::STOCK_DAYS)];

        if(! $model->save()){
            echo print_r($model);
            exit;
        }
        return true;
    }

    private function updateModelByParamsNames($row){
        $secondParam = $row[$this->offsetLeter($this->price_param_second_id)];
        $firstParam = $row[$this->offsetLeter($this->price_param_id)];
        $productPriceParam = ProductPriceParam::findOne([
            'product_id' => $row[$this->offsetLeter(self::ID)],
            'name' => $this->price_param,
        ]);
        $productPriceParamSecond = ProductPriceParam::findOne([
            'product_id' => $row[$this->offsetLeter(self::ID)],
            'name' => $this->price_param_second,
        ]);
        if($productPriceParam && !$productPriceParamSecond) {
            $needId = ProductPriceParamValue::findOne([
                'product_price_param_id' => $productPriceParam->id,
                'name' => $firstParam
            ]);
            if($needId) {
                $needId = $needId->id;
                $model = ProductPrice::find()->where([
                    'param_value_id' => $needId,
                    'param_value_second_id' => null
                ])->one();
                if($model) {
                    return $this->updateModel($model, $row);
                }
            }
        } elseif($productPriceParam && $productPriceParamSecond) {
            $needIdFirst = ProductPriceParamValue::findOne([
                'product_price_param_id' => $productPriceParam->id,
                'name' => $firstParam
            ]);
            $needIdSecond = ProductPriceParamValue::findOne([
                'product_price_param_id' => $productPriceParamSecond->id,
                'name' => $secondParam
            ]);
            if($needIdFirst && $needIdSecond) {
                $needIdFirst = $needIdFirst->id;
                $needIdSecond = $needIdSecond->id;
                $model = ProductPrice::find()->where([
                    'param_value_id' => $needIdFirst,
                    'param_value_second_id' => $needIdSecond
                ])->orWhere([
                    'param_value_id' => $needIdSecond,
                    'param_value_second_id' => $needIdFirst
                ])->one();
                if($model) {
                    return $this->updateModel($model, $row);
                }
            }
        }
        return true;
    }

    private function getCategoryParams($titles, $row){
        $this->offset = 20 - count($titles);
        $this->price_param = $this->getFirstParam($titles, $row);
        $this->price_param_second = $this->getSecondParam($titles, $row, $this->price_param);

    }

    private function getFirstParam($titles, $row){
        if(count($titles) < 15) return null;
        $array = [self::PARAM_1, self::PARAM_2, self::PARAM_3, self::PARAM_4, self::PARAM_5, self::PARAM_6];
        for($i = 0; $i <= count($titles) - 15; $i++) {
            if($row[$array[$i]]) {
                $this->price_param_id = $array[$i];
                return $titles[$array[$i]];
            }
        }
    }

    private function getSecondParam($titles, $row, $first){
        if(count($titles) < 15) return null;
        $array = [self::PARAM_1, self::PARAM_2, self::PARAM_3, self::PARAM_4, self::PARAM_5, self::PARAM_6];
        for($i = 0; $i <= count($titles) - 15; $i++) {
            if($row[$array[$i]] && $titles[$array[$i]] !== $first) {
                $this->price_param_second_id = $array[$i];
                return $titles[$array[$i]];
            }
        }
    }

    private function offsetLeter($leter){
        $alphabet = $this->getAlphabet();
        $index = array_search($leter, $alphabet);
        if($index > CategoryExportStrategy::NO_MOVE_OFFSET) {
            return $alphabet[$index-$this->getOffset()];
        } return $leter;
    }

    private function getAlphabet(){
        return [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'
        ];
    }

    private function getOffset(){
        return $this->offset;
    }

    private function updatePrices($item) {
        $resultPrice = \Yii::$app->db->createCommand("
                select * from product_prices where `value` = (
                    select min(value) from product_prices where product_id = '$item' AND `value` IS NOT NULL 
                ) AND product_id = '$item'
            ")->queryOne();
        $resultStock = \Yii::$app->db->createCommand("
                select MAX(case when stock = 'IN_SHOP' then 3 when stock = 'WAITING' then 1 when stock = 'STOCK' then 2 else 0 END) as weight
                 from product_prices where product_id = '$item'
            ")->queryOne();

        $tmp = $this->getStockByWeigh($resultStock['weight']);
        $bestStock = $tmp ? "'". $tmp . "'" : 'NULL';
        $price = $resultPrice['value'] ?? 'NULL';


        $disckount_mode = $resultPrice['discount_mode'] ?? 'NULL';
        $disckount_mode = $disckount_mode != 'NULL' ? "'" . $disckount_mode . "'" : 'NULL';
        $discount_value = $resultPrice['discount_value'] ?? 'NULL';
        $discount_value = $discount_value != 'NULL' ? "'" . $discount_value . "'" : 'NULL';
        $tmp = $this->getPriceWithDiscount($price, $resultPrice['discount_value'] ?? null, $disckount_mode);
        $price_with_discount = $tmp ?? 'NULL';

        \Yii::$app->db->createCommand("
                UPDATE products SET 
                    price = $price,
                    discount_mode = $disckount_mode,
                    discount_value = $discount_value,
                    price_with_discount = $price_with_discount,
                    stock = $bestStock
                WHERE id = '$item'
            ")->execute();
    }


    private function getStockByWeigh($weight) {
        switch ($weight) {
            case 3:
                return 'SHOP';
            case 2:
                return 'STOCK';
            case 1:
                return 'WAITING';
            default:
                return 'NO';
        }
    }

    private function getPriceWithDiscount($price, $discount_value, $discountType) {
        if(!is_numeric($price)) {
            return null;
        }
        switch ($discountType) {
            case "NULL":
                return $price;
            case "'PERCENT'":
                return $price - floor($price / 100 * $discount_value);
            case "'FIXED'":
                return $discount_value;
            case "'AMOUNT'":
                return $price - $discount_value;
        }
    }
}

<?php
namespace ozerich\shop\components\importPrices\strategies;


use moonland\phpexcel\Excel;
use ozerich\shop\components\importPrices\ImportPricesStrategyInterface;
use ozerich\shop\constants\DiscountType;
use ozerich\shop\constants\Stock;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductPrice;
use ozerich\shop\models\ProductPriceParam;
use ozerich\shop\models\ProductPriceParamValue;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class CategoryImportStrategy implements ImportPricesStrategyInterface
{
    private $offset = 0;
    private $price_param;
    private $price_param_second;

    private $load_params = false;

    private $_file;
    private $lines = 0;
    private $products = [];

    // columns //
    const ID = 'A';
    const ID_PRICE = 'M';
    const VALID = 'N';
    const PRICE = 'E';
    const PRICE_WITH_PROMO = 'F';
    const PERCENT = 'G';
    const PROMO_AMOUNT = 'H';
    const STOCK = 'J';
    const STOCK_DAYS = 'K';
    const PARAM_FIRST = 'D';
    const PARAM_SECOND = 'C';

    public function init($file){
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
            foreach ($data as $sheet) {
                if(!$this->load_params) {
                    $this->getCategoryParams($sheet);
                    $this->load_params = true;
                }
                if($sheet && is_array($sheet)) {
                    if($sheet[self::ID] !== 'ID') {
                        $result = $this->load($sheet);
                        if(!$result) return false;
                    }
                }
            }
            return "Импортировано ". count($this->products) . " товаров (" . $this->lines . " позиций)";
        }
        return false;
    }

    private function load($row){
        if(!$this->validateRow($row)) return false;
        $product = Product::findOne($row[self::ID]);
        if($product) {
            $this->lines++;
            $this->products[$product->id] = true;
            if($row[$this->offsetLeter(self::ID_PRICE)]) {
                $productPrice = ProductPrice::findOne($row[$this->offsetLeter(self::ID_PRICE)]);
                if($productPrice) {
                    return $this->updateModel($productPrice, $row);
                } else {
                    return $this->updateModelByParamsNames($row);
                }
            } else {
                return $this->updateModel($product, $row);
            }
        } elseif(!$row[$this->offsetLeter(self::ID)]) return true;
        return false;
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
            $model->price = $row[$this->offsetLeter(self::PRICE)];
        } else {
            $model->value = $row[$this->offsetLeter(self::PRICE)];
        }
        $model->stock = Stock::toValue($row[$this->offsetLeter(self::STOCK)]);
        $model->stock_waiting_days = (int)$row[$this->offsetLeter(self::STOCK_DAYS)];

        return $model->save();
    }

    private function updateModelByParamsNames($row){
        $secondParam = $row[$this->offsetLeter(self::PARAM_SECOND)];
        $firstParam = $row[$this->offsetLeter(self::PARAM_FIRST)];
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

    private function getCategoryParams($titles){
        if(count($titles) == 15) {
            $this->offset = 0;
            $this->price_param = $titles['C'];
            $this->price_param_second = $titles['D'];
        } elseif (count($titles) == 14) {
            $this->offset = 1;
            $this->price_param = $titles['C'];
        } else {
            $this->offset = 2;
        }
    }

    private function offsetLeter($leter){
        $alphabet = $this->getAlphabet();
        $index = array_search($leter, $alphabet);
        if($index > 3) {
            return $alphabet[$index-$this->getOffset()];
        } return $leter;
    }

    private function getAlphabet(){
        return [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O'
        ];
    }

    private function getOffset(){
        return $this->offset;
    }
}

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

class CategoryImportStrategy implements ImportPricesStrategyInterface
{
    private $_file;

    // columns //
    const ID = 'A';
    const ID_PRICE = 'M';
    const VALID = 'N';
    const PRICE = 'E';
    const PRICE_WITH_PROMO = 'F';
    const PERCENT = 'G';
    const PROMO_AMOUNT = 'H';
    const STOCK = 'J';
    const STOCK_DAYS = 'J';
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
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $exception){
            return false;
        }
        if($data && is_array($data)){
            foreach ($data as $sheet) {
                if($sheet && is_array($sheet)) {
                    if($sheet[self::ID] !== 'ID') {
                        $result = $this->load($sheet);
                        if(!$result) return false;
                    }
                }
            }
            return true;
        }
        return false;
    }

    private function load($row){
        if(!$this->validateRow($row)) return false;
        $product = Product::findOne($row[self::ID]);
        if($product) {
            if($row[self::ID_PRICE]) {
                $productPrice = ProductPrice::findOne($row[self::ID_PRICE]);
                if($productPrice) {
                    return $this->updateModel($productPrice, $row);
                } else {
                    return $this->updateModelByParamsNames($row);
                }
            } else {
                return $this->updateModel($product, $row);
            }
        } elseif(!$row[self::ID]) return true;
        return false;
    }

    private function validateRow($row){
        if($row[self::ID] && !$row[self::ID_PRICE]){
            return true;
        }
        elseif(($row[self::ID] * ($row[self::ID_PRICE] + 3)) == $row[self::VALID]) {
            return true;
        }
        return false;
    }

    private function updateModel($model, $row){
        if($row[self::PRICE_WITH_PROMO]){
            $model->discount_mode = DiscountType::FIXED;
            $model->discount_value = $row[self::PRICE_WITH_PROMO];
        } elseif($row[self::PERCENT]) {
            $model->discount_mode = DiscountType::PERCENT;
            $model->discount_value = $row[self::PERCENT];
        } elseif($row[self::PROMO_AMOUNT]) {
            $model->discount_mode = DiscountType::AMOUNT;
            $model->discount_value = $row[self::PROMO_AMOUNT];
        } else {
            $model->discount_mode = null;
            $model->discount_value = null;
        }

        if($model INSTANCEOF Product){
            $model->price = $row[self::PRICE];
        } else {
            $model->value = $row[self::PRICE];
        }
        $model->stock = Stock::toValue($row[self::STOCK]);
        $model->stock_waiting_days = (int)$row[self::STOCK_DAYS];

        return $model->save();
    }

    private function updateModelByParamsNames($row){
        $secondParam = $row[self::PARAM_SECOND];
        $firstParam = $row[self::PARAM_FIRST];
        $productPriceParam = ProductPriceParam::findOne([
            'product_id' => $row[self::ID],
            'name' => 'Обивка',
        ]);
        $productPriceParamSecond = ProductPriceParam::findOne([
            'product_id' => $row[self::ID],
            'name' => 'Комплектация',
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
}

<?php

namespace ozerich\shop\modules\api\responses\products;

use ozerich\api\response\BaseResponse;
use ozerich\shop\models\ProductPriceParam;
use ozerich\shop\models\ProductPriceParamValue;
use ozerich\shop\modules\api\models\ProductPriceDTO;

class PricesResponse extends BaseResponse
{
    private $params = [];

    private $prices = [];

    public function setParams($value)
    {
        $this->params = $value;
    }

    public function setPrices($value)
    {
        $this->prices = $value;
    }

    private function getPricesJSON()
    {
        $result = [];

        foreach ($this->prices as $price) {
            $key = $price->param_value_id . ($price->param_value_second_id ? 'x' . $price->param_value_second_id : '');
            $result[$key] = (new ProductPriceDTO($price))->toJSON();
        }

        return $result;
    }

    public function toJSON()
    {
        return [
            'params' => array_map(function (ProductPriceParam $param) {
                return [
                    'id' => $param->id,
                    'name' => $param->name,
                    'values' => array_map(function (ProductPriceParamValue $value) {
                        return [
                            'id' => $value->id,
                            'name' => $value->name,
                            'description' => $value->description
                        ];
                    }, $param->productPriceParamValues)
                ];
            }, $this->params),

            'prices' => $this->getPricesJSON()
        ];
    }
}
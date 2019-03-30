<?php

namespace ozerich\shop\modules\api\responses\products;

use ozerich\api\response\BaseResponse;
use ozerich\shop\models\Product;
use ozerich\shop\modules\api\models\ProductDTO;

class ProductSectionsResponse extends BaseResponse
{
    private $sections = [];

    public function add($name, $items)
    {
        $this->sections[] = ['name' => $name, 'items' => $items];
    }

    public function toJSON()
    {
        return array_map(function ($section) {
            return [
                'name' => $section['name'],
                'items' => array_map(function (Product $product) {
                    return (new ProductDTO($product))->toJSON();
                }, $section['items'])
            ];
        }, $this->sections);
    }
}
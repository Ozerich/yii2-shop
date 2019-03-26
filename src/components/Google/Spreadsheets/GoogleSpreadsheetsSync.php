<?php

namespace ozerich\shop\components\Google\Spreadsheets;

use ozerich\shop\models\Category;
use ozerich\shop\models\Product;
use ozerich\shop\models\ProductPrice;
use ozerich\shop\models\ProductPriceParam;
use ozerich\shop\models\ProductPriceParamValue;
use yii\base\Component;

class GoogleSpreadsheetsSync extends Component
{
    public $spreadsheet_id;

    /**
     * @return GoogleSpreadsheets
     */
    public function spreadsheet()
    {
        return \Yii::$app->spreadsheets;
    }

    public function syncToGoogle(Category $category)
    {
        $this->spreadsheet()->addSheet($this->spreadsheet_id, $category->name);

        /** @var \ozerich\shop\models\Product[] $products */
        $products = \ozerich\shop\models\Product::find()
            ->joinWith('productCategories')
            ->joinWith('productPriceParams')
            ->andWhere('category_id=:category_id', [':category_id' => $category->id])
            ->addOrderBy('products.name ASC')
            ->all();

        $priceParams = [];
        foreach ($products as $product) {
            foreach ($product->productPriceParams as $productPriceParam) {
                if (!in_array($productPriceParam->name, $priceParams)) {
                    $priceParams[] = $productPriceParam->name;
                }
            }
        }

        $priceParams = array_reverse($priceParams);

        $header = ['ID', 'Название'];

        foreach ($priceParams as $param) {
            $header[] = $param;
        }

        $header[] = 'Цена';

        $rows = [$header];
        foreach ($products as $product) {
            $product_price_params = [];
            $product_price_param_values = [];

            foreach ($priceParams as $priceParam) {
                $product_price_params[$priceParam] = null;
                $product_price_param_values[$priceParam] = [];

                $productPriceParam = ProductPriceParam::find()
                    ->andWhere('product_id=:product_id', [':product_id' => $product->id])
                    ->andWhere('name=:name', [':name' => $priceParam])
                    ->one();

                if (!$productPriceParam) {
                    continue;
                }

                $product_price_params[$priceParam] = $productPriceParam;
                $product_price_param_values[$priceParam] = ProductPriceParamValue::find()
                    ->andWhere('product_price_param_id=:param_id', [':param_id' => $productPriceParam->id])
                    ->all();
            }

            $product_rows = [];

            if (count($priceParams) == 2) {
                $first_column = isset($product_price_param_values[$priceParams[0]]) ? $product_price_param_values[$priceParams[0]] : [];
                $second_column = isset($product_price_param_values[$priceParams[1]]) ? $product_price_param_values[$priceParams[1]] : [];

                if (empty($first_column)) {
                    foreach ($second_column as $item) {
                        $product_rows[] = [null, $item];
                    }
                } else if (empty($second_column)) {
                    foreach ($first_column as $item) {
                        $product_rows[] = [$item, null];
                    }
                } else {
                    for ($i = 0; $i < count($first_column); $i++) {
                        for ($j = 0; $j < count($second_column); $j++) {
                            $product_rows[] = [$first_column[$i], $second_column[$j]];
                        }
                    }
                }

                foreach ($product_rows as $product_row) {
                    $priceModel = ProductPrice::findByParamIds($product_row[1] ? $product_row[1]->id : null, $product_row[0] ? $product_row[0]->id : null)->one();
                    $price = $priceModel ? $priceModel->value : '';

                    $rows[] = [
                        $product->id,
                        $product->name,
                        $product_row[0] ? $product_row[0]->name : '',
                        $product_row[1] ? $product_row[1]->name : '',
                        $price
                    ];
                }
            } else if (count($priceParams) == 1) {
                $first_column = isset($product_price_param_values[$priceParams[0]]) ? $product_price_param_values[$priceParams[0]] : [];
                foreach ($first_column as $item) {
                    $product_rows[] = [$item, null];
                }

                foreach ($product_rows as $product_row) {
                    $priceModel = ProductPrice::findByParamIds($product_row[0]->id, null)->one();
                    $price = $priceModel ? $priceModel->value : '';

                    $rows[] = [
                        $product->id,
                        $product->name,
                        $product_row[0] ? $product_row[0]->name : '',
                        $price
                    ];
                }
            } else if (count($priceParams) == 0) {
                $rows[] = [
                    $product->id,
                    $product->name,
                    $product->price
                ];
            }
        }

        $this->spreadsheet()->setSheetData($this->spreadsheet_id, $category->name, $rows);
    }

    private function syncProductWithOneParam(Product $product, $param, $value, $price)
    {
        $paramModel = ProductPriceParam::find()->andWhere('product_id=:product_id', [':product_id' => $product->id])
            ->andWhere('name=:name', [':name' => $param])
            ->one();

        if (!$paramModel) {
            return;
        }

        $paramValueModel = ProductPriceParamValue::find()
            ->andWhere('product_price_param_id=:param_id', [':param_id' => $paramModel->id])
            ->andWhere('name=:name', [':name' => $value])
            ->one();

        if (!$paramValueModel) {
            return;
        }

        $priceModel = ProductPrice::findByParamIds($paramValueModel->id, null)->one();
        if (!$priceModel) {
            if (empty($price)) {
                return;
            }
            $priceModel = new ProductPrice();
            $priceModel->product_id = $product->id;
            $priceModel->param_value_id = $paramValueModel->id;
        }

        if (empty($price)) {
            $priceModel->delete();
        } else {
            $priceModel->value = $price;
            $priceModel->save();
        }
    }

    public function syncFromGoogle(Category $category)
    {
        $rows = $this->spreadsheet()->getSheetData($this->spreadsheet_id, $category->name);
        if ($rows == null) {
            return;
        }

        $header = $rows[0];

        $params_count = count($header) - 3;

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $product = Product::findOne($row[0]);
            if (!$product) {
                continue;
            }

            if ($params_count == 0) {
                $product->price = $row[2];
                $product->save(false, ['price']);
            } else if ($params_count == 1) {
                $this->syncProductWithOneParam($product, $header[2], $row[2], $row[3]);
            } else if ($params_count == 2) {
                $paramModel = ProductPriceParam::find()->andWhere('product_id=:product_id', [':product_id' => $product->id])
                    ->andWhere('name=:name', [':name' => $header[3]])
                    ->one();

                $secondParamModel = ProductPriceParam::find()->andWhere('product_id=:product_id', [':product_id' => $product->id])
                    ->andWhere('name=:name', [':name' => $header[2]])
                    ->one();

                if (!$paramModel) {
                    $this->syncProductWithOneParam($product, $header[2], $row[2], $row[4]);
                    continue;
                }

                if (!$secondParamModel) {
                    $this->syncProductWithOneParam($product, $header[3], $row[3], $row[4]);
                    continue;
                }

                $paramValueModel = ProductPriceParamValue::find()
                    ->andWhere('product_price_param_id=:param_id', [':param_id' => $paramModel->id])
                    ->andWhere('name=:name', [':name' => $row[3]])
                    ->one();

                $paramValueSecondModel = ProductPriceParamValue::find()
                    ->andWhere('product_price_param_id=:param_id', [':param_id' => $secondParamModel->id])
                    ->andWhere('name=:name', [':name' => $row[2]])
                    ->one();

                if (!$paramValueModel || !$paramValueSecondModel) {
                    continue;
                }

                $price = ProductPrice::findByParamIds($paramValueModel->id, $paramValueSecondModel->id)->one();
                if (!$price) {
                    $price = new ProductPrice();
                    $price->product_id = $product->id;
                    $price->param_value_id = $paramValueModel->id;
                    $price->param_value_second_id = $paramValueSecondModel->id;
                }

                $price->value = $row[4];
                $price->save();
            }
        }
    }
}
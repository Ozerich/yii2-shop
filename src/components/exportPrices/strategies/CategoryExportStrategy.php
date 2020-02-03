<?php
namespace ozerich\shop\components\exportPrices\strategies;

use ozerich\shop\components\exportPrices\ExportPricesStrategyInterface;
use ozerich\shop\constants\CategoryType;
use ozerich\shop\constants\DiscountType;
use ozerich\shop\constants\Stock;
use ozerich\shop\models\Category;
use ozerich\shop\models\Product;

class CategoryExportStrategy implements ExportPricesStrategyInterface
{
    private $_category;
    private $_titles = [
        'ID',
        'Название',
        'Комплектация',
        'Обивка',
        'Цена',
        'Цена со скидкой',
        'Процент скидки',
        'Сумма скидки',
        'ИТОГ',
        'Наличие',
        'Кол-во дней',
        'Комментарий',
        '--',
        '--',
    ];

    public function init($category){
        $this->_category = $category;
    }

    public function export(){
        $products = Product::findAll([
            'category_id' => $this->getAllChildCategories([], $this->_category->id)
        ]);
        $array = [];
        foreach ($products as $product) {
            $_productPrices = $product->prices;
            if(count($_productPrices)) {
                foreach ($_productPrices as $_productPrice) {
                    $param = $_productPrice->paramValue;
                    $paramSecond = $_productPrice->paramSecondValue;
                    $modify = $weight = '';
                    if($param && $param->productPriceParam->name == 'Комплектация') {
                        $modify = $param->name;
                    } elseif($paramSecond && $paramSecond->productPriceParam->name == 'Комплектация') {
                        $modify =  $paramSecond->name;
                    }
                    if($param && $param->productPriceParam->name == 'Обивка') {
                        $weight = $param->name;
                    } elseif($paramSecond && $paramSecond->productPriceParam->name == 'Обивка') {
                        $weight = $paramSecond->name;
                    }
                    $array[] = [
                        $product->id,
                        $product->name,
                        $modify,
                        $weight,
                        $_productPrice->value,
                        $_productPrice->discount_mode == DiscountType::FIXED ? $_productPrice->discount_value : null,
                        $_productPrice->discount_mode == DiscountType::PERCENT ? $_productPrice->discount_value : null,
                        $_productPrice->discount_mode == DiscountType::AMOUNT ? $_productPrice->discount_value : null,
                        $priority = $param->priority,
                        Stock::toLabel($_productPrice->stock), // J
                        $_productPrice->stock_waiting_days, // K
                        $_productPrice->comment, // L
                        $_productPrice->id, // M
                        ($product->id * ($_productPrice->id + 3) ) // N
                    ];
                }
            } else {
                $array[] = [
                    $product->id,
                    $product->name,
                    null,
                    null,
                    $product->price,
                    $product->discount_mode == DiscountType::FIXED ? $product->discount_value : null,
                    $product->discount_mode == DiscountType::PERCENT ? $product->discount_value : null,
                    $product->discount_mode == DiscountType::AMOUNT ? $product->discount_value : null,
                    $priority = 1,
                    null, // J
                    null, // K
                    $product->price_comment, // L
                ];
            }
        }
        array_multisort(array_column($array, 0),  SORT_ASC,
            array_column($array, 2), SORT_ASC,
            array_column($array, 8), SORT_ASC,
            $array);
        foreach ($array as $key => $value) {
            $num = $key + 2;
            $array[$key][8] = "= IF(ISBLANK(F$num), IF(ISBLANK(G$num),  E$num-H$num, E$num * ((100-G$num)/100)), F$num)";
//                        "= ЕСЛИ(ЕПУСТО(F$num); ЕСЛИ(ЕПУСТО(G$num);  E$num-H$num; E117 * ((100-G$num)/100)); F$num)"
        }
        return $this->createExelFile($array)->send('export.xlsx');
    }

    private function getAllChildCategories($array, $id){
        $new_array = [$id];
        foreach (Category::findAll(['parent_id' => $id, 'type' => CategoryType::CATALOG]) as $item) {
            $new_array = array_merge($this->getAllChildCategories($array, $item->id), $new_array);
        }
        return $new_array;
    }

    private function createExelFile($array) {
        $file = \Yii::createObject([
            'class' => 'codemix\excelexport\ExcelFile',
            'sheets' => [
                'Categories' => [
                    'data' => $array,
                    'titles' => $this->getExelTitles(),
                    'styles' => $this->getExelColumnStyles(),
                    'callbacks' => [
                        'J' => function ($cell, $row, $column) { $this->setExelSelect($cell, $row, $column); },
                    ],
                ]
            ]
        ]);

        return $this->setExelParams($file);
    }

    private function getExelColumnStyles() {
        return [
            'A1:A9999' => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FF0000'],
                ]
            ],
            'E1:E9999' => [
                'font' => [
                    'bold' => true,
                ]
            ],
            'F1:H9999' => [
                'font' => [
                    'color' => ['rgb' => '4F8212'],
                ]
            ],
            'I1:I9999' => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => '755aec'],
                ]
            ],
            'M1:N9999' => [
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'],
                ]
            ],
        ];
    }

    private function setExelSelect($cell, $row, $column){
        $objValidation = $cell->getDataValidation();
        $objValidation->setType( \PHPExcel_Cell_DataValidation::TYPE_LIST );
        $objValidation->setErrorStyle( \PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
        $objValidation->setAllowBlank(false);
        $objValidation->setShowInputMessage(true);
        $objValidation->setShowErrorMessage(true);
        $objValidation->setShowDropDown(true);
        $objValidation->setErrorTitle('Ошибка ввода');
        $objValidation->setError('Значение неверное');
        $objValidation->setPromptTitle('Выбрать из списка');
        $objValidation->setPrompt('Наличие товара на складе');
        $objValidation->setFormula1('"Нет в наличии,Под заказ,В магазине,На складе"');
    }

    private function setExelParams($file){
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('B')->setWidth(25);
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('H')->setWidth(13);
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('J')->setWidth(12);
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('K')->setWidth(12);
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('L')->setWidth(50);
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('M')->setWidth(0.01);
        $file->getWorkbook()->getActiveSheet()->getColumnDimension('N')->setWidth(0.01);

        $file->getWorkbook()->getActiveSheet()->freezePane('A2');

        $file->getWorkbook()->getActiveSheet()->getProtection()->setSheet(true);
        $file->getWorkbook()->getActiveSheet()->getProtection()->setSort(true);
        $file->getWorkbook()->getActiveSheet()->getProtection()->setInsertRows(true);
        $file->getWorkbook()->getActiveSheet()->getProtection()->setFormatCells(true);

        $file->getWorkbook()->getActiveSheet()->getProtection()->setPassword('password');
        $file->getWorkbook()->getActiveSheet()->getStyle('E2:H9999')->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        $file->getWorkbook()->getActiveSheet()->getStyle('J2:L9999')->getProtection()->setLocked(\PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
        return $file;
    }

    private function getExelTitles(){
        return $this->_titles;
    }
}

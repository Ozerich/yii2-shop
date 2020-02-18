<?php
namespace ozerich\shop\components\exportPrices\strategies;

use ozerich\shop\components\exportPrices\ExportPricesStrategyInterface;
use ozerich\shop\constants\CategoryType;
use ozerich\shop\constants\DiscountType;
use ozerich\shop\constants\Stock;
use ozerich\shop\models\Category;
use ozerich\shop\models\Product;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class CategoryExportStrategy implements ExportPricesStrategyInterface
{
    private $param_name;
    private $param_name_second;
    /**
     * @var Spreadsheet
     */
    private $_spreadsheet;
    /**
     * @var Worksheet
     */
    private $_sheet;

    const STOCK = 'P';
    const FORMULA = 14;
    const NO_MOVE_OFFSET = 9; // Кол-во столбцов, которые не сдвигаются -1

    private $_filename;
    private $_params;
    private $_category;
    private $_manufacture;
    private $_without_price;

    private $titles = [
        'A' => 'ID',                //      /\
        'B' => 'Производитель',     //     /||\
        'C' => 'Название',          //    //||\\
        'D' => 'Маркировка',        //      ||
        'E' => 'Параметр 1',        //      ||
        'F' => 'Параметр 2',        //      ||
        'G' => 'Параметр 3',        //      ||
        'H' => 'Параметр 4',        //      ||
        'I' => 'Параметр 5',        //      ||
        'J' => 'Параметр 6',        //      ||
        'K' => 'Цена',
        'L' => 'Цена со скидкой',
        'M' => 'Процент скидки',
        'N' => 'Сумма скидки',
        'O' => 'ИТОГ',
        'P' => 'Наличие',
        'Q' => 'Кол-во дней',
        'R' => 'Комментарий',
        'S' => '--',
        'T' => '--',
    ];

    public function init($params, $filename, $category, $manufacture, $without_price){
        ini_set('max_execution_time', 100);
        $this->_filename = $filename;
        $this->_params = $params ? $params : [];
        $this->_category = $category;
        $this->_manufacture = $manufacture;
        $this->_without_price = $without_price;
    }

    public function export(){
        $products = Product::find()->where([
            'category_id' => $this->getAllChildCategories([], $this->_category->id),
        ]);
        if($this->_manufacture) {
            $products->andWhere([
                'manufacture_id' => $this->_manufacture,
            ]);
        }
        if($this->_without_price == 'true') {
            $products->andWhere([
                'price' => null
            ]);
        }
        $products = $products->all();
        $array = [];
        foreach ($products as $product) {
            $_productPrices = $product->prices;
            if(count($_productPrices)) {
                foreach ($_productPrices as $_productPrice) {
                    $param = $_productPrice->paramValue;
                    $paramSecond = $_productPrice->paramSecondValue;

                    if(($this->_without_price == 'true') && $_productPrice->value > 0) {
                        continue;
                    }
                    $first = false;
                    if($param && $paramSecond) {
                        if($param->productPriceParam->priority > $paramSecond->productPriceParam->priority){
                            $first = true;
                            $priorityNew = $param->productPriceParam->priority;
                        } else {
                            $priorityNew = $paramSecond->productPriceParam->priority;
                        }
                    } else {
                        $priorityNew = $param->productPriceParam->priority;
                    }
                    $array[] = [
                        $product->id,
                        $product->manufacture ? $product->manufacture->name : '',
                        $product->name,
                        $product->label,

                        $this->getParamValue($param, $paramSecond, 0),
                        $this->getParamValue($param, $paramSecond, 1),
                        $this->getParamValue($param, $paramSecond, 2),
                        $this->getParamValue($param, $paramSecond, 3),
                        $this->getParamValue($param, $paramSecond, 4),
                        $this->getParamValue($param, $paramSecond, 5),

                        $_productPrice->value,
                        $_productPrice->discount_mode == DiscountType::FIXED ? $_productPrice->discount_value : null,
                        $_productPrice->discount_mode == DiscountType::PERCENT ? $_productPrice->discount_value : null,
                        $_productPrice->discount_mode == DiscountType::AMOUNT ? $_productPrice->discount_value : null,
                        $priority = !$first ? $param->priority : $paramSecond->priority,
                        Stock::toLabel($_productPrice->stock), // J
                        $_productPrice->stock_waiting_days, // K
                        $_productPrice->comment, // L
                        $_productPrice->id, // M
                        ($product->id * ($_productPrice->id + 3) ), // N
                        $product->popular_weight,
                        $priorityNew
                    ];
                }
            } else {
                $array[] = [
                    $product->id,
                    $product->manufacture ? $product->manufacture->name : '',
                    $product->name,
                    $product->label,
                    null,
                    null,
                    null,
                    null,
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
                    null, //M
                    null, // N,
                    $product->popular_weight,
                    1
                ];
            }
        }
        array_multisort(
            array_column($array, 20),  SORT_DESC,
            array_column($array, 0),  SORT_ASC,
            array_column($array, 14), SORT_ASC,
            array_column($array, 21), SORT_ASC,
            array_column($array, 4), SORT_ASC,
            $array);
        foreach ($array as $key => $value) {
            $num = $key + 2;
            $array[$key][self::FORMULA] = "= IF(ISBLANK(" . $this->offsetLeter('L'). "$num), IF(ISBLANK("
                . $this->offsetLeter('M'). "$num),  "
                . $this->offsetLeter('K'). "$num-"
                . $this->offsetLeter('N'). "$num, "
                . $this->offsetLeter('K'). "$num * ((100-"
                . $this->offsetLeter('M'). "$num)/100)), "
                . $this->offsetLeter('L'). "$num)";
        }
        return $this->createExelFile($array);
    }

    private function getAllChildCategories($array, $id){
        $new_array = [$id];
        foreach (Category::findAll(['parent_id' => $id, 'type' => CategoryType::CATALOG]) as $item) {
            $new_array = array_merge($this->getAllChildCategories($array, $item->id), $new_array);
        }
        return $new_array;
    }

    private function createExelFile($array) {
        $this->_spreadsheet = new Spreadsheet();
        $this->_sheet = $this->_spreadsheet->getActiveSheet();
        $this
            ->setExcelMetaData()
            ->setExcelColumnTitles()
            ->setExcelColumnStyles()
            ->setExcelColumnSizes()
            ->setExcelColumnProtection()
            ->setExcelCellValues($array);

        $writer = new Xlsx($this->_spreadsheet);
        return $this->send($writer);
    }

    private function setExcelMetaData() {
        $this->_spreadsheet->getProperties()->setCreator('BelMebel.by')
            ->setLastModifiedBy('BelMebel')
            ->setTitle('Экспорт цен из категории')
            ->setSubject('Экспорт цен из категории');
        return $this;
    }

    private function setExcelColumnStyles() {
        $A = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FF0000'],
            ]
        ];
        $E = [
            'font' => [
                'bold' => true,
            ]
        ];
        $I = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '755aec'],
            ]
        ];
        $F = [
            'font' => [
                'color' => ['rgb' => '4F8212'],
            ]
        ];
        $M = [
            'font' => [
                'color' => ['rgb' => 'FFFFFF'],
            ]
        ];
        $this->_sheet->getStyle($this->offsetLeter('A') . '1:' . $this->offsetLeter('A') . '9999')->applyFromArray($A);
        $this->_sheet->getStyle($this->offsetLeter('K') . '1:' . $this->offsetLeter('K') . '9999')->applyFromArray($E);
        $this->_sheet->getStyle($this->offsetLeter('O') . '1:' . $this->offsetLeter('O') . '9999')->applyFromArray($I);
        $this->_sheet->getStyle($this->offsetLeter('L') . '1:' . $this->offsetLeter('N') . '9999')->applyFromArray($F);
        $this->_sheet->getStyle($this->offsetLeter('S') . '1:' . $this->offsetLeter('T') . '9999')->applyFromArray($M);
        return $this;
    }

    private function setExcelColumnSizes(){
        $sizes = [
            'A' => 5,
            'B' => 20,
            'C' => 25,
            'D' => 15,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
            'J' => 20,
            'K' => 15,
            'L' => 15,
            'M' => 15,
            'N' => 15,
            'O' => 13,
            'P' => 12,
            'Q' => 12,
            'R' => 50,
            'S' => 0.01,
            'T' => 0.01
        ];
        foreach ($sizes as $key => $size) {
            $this->_sheet->getColumnDimension($this->offsetLeter($key))->setWidth($size);
        }
        return $this;
    }

    private function setExcelColumnProtection(){
        $this->_sheet->freezePane('A2');
        $this->_sheet->getProtection()->setSheet(true);
        $this->_sheet->getProtection()->setSort(true);
        $this->_sheet->getProtection()->setInsertRows(true);
        $this->_sheet->getProtection()->setFormatCells(true);

        $this->_sheet->getProtection()->setPassword('BelmebelExp');
        $this->_sheet->getStyle($this->offsetLeter('K') . '2:' . $this->offsetLeter('N') . '9999')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $this->_sheet->getStyle($this->offsetLeter('O') . '2:'. $this->offsetLeter('R') . '9999')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        return $this;
    }

    private function setExcelColumnTitles(){
        foreach ($this->_params as $index => $param) {
            $this->titles[
                $this->getAlphabet()[$index + 4]
            ] = $param;
        }
        foreach ($this->titles as $key => $title) {
            $this->_sheet->setCellValue($this->offsetLeter($key)."1", $title);
        }
        return $this;
    }

    private function setExcelCellValues($array){
        $x = 2;
        foreach ($array as $item) {
            foreach(range('A', 'T') as $key => $columnID) {
                if(array_key_exists($key, $item)) {
                    $this->_sheet->setCellValue($this->offsetLeter($columnID).$x, $item[$key]);
                    if($columnID == self::STOCK) {
                        $this->setExelSelect($this->_sheet->getCell($this->offsetLeter($columnID).$x));
                    }
                }
            }
            $x++;
        }
        return $this;
    }

    /**
     * @param Cell $sell
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function setExelSelect($sell){
        $objValidation = $sell->getDataValidation();
        $objValidation->setType( DataValidation::TYPE_LIST );
        $objValidation->setErrorStyle( DataValidation::STYLE_INFORMATION );
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

    private function send($writer){
        $filename = str_replace(' ', '_', $this->_filename) . "_" . date('Y-m-d') . '.xlsx';
//        header('Content-Type: application/vnd.ms-excel');
//        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer->save("uploads/$filename");
        return "/uploads/$filename";
    }

    private function offsetLeter($leter){
        $alphabet = $this->getAlphabet();
        $index = array_search($leter, $alphabet);
        if($index > self::NO_MOVE_OFFSET) {
            return $alphabet[$index-$this->getOffset()];
        } return $leter;
    }

    private function getAlphabet(){
        return [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T'
        ];
    }

    private function getOffset(){
        return 6 - count($this->_params);
    }


    private function getParamValue($firstParam, $secondParam, $i) {
        $result = null;
        if(array_key_exists($i, $this->_params)){
            if($firstParam && $firstParam->productPriceParam && $firstParam->productPriceParam->name) {
                $result = $firstParam->productPriceParam->name === $this->_params[$i] ? $firstParam->name : null;
            }
            if($secondParam && $secondParam->productPriceParam && $secondParam->productPriceParam->name) {
                $result = !$result && $secondParam->productPriceParam->name === $this->_params[$i] ? $secondParam->name : $result;
            }
        }
        return $result;
    }
}

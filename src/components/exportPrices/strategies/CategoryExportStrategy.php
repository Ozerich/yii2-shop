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

    const STOCK = 'L';
    const FORMULA = 10;
    const NO_MOVE_OFFSET = 5; // Кол-во столбцов, которые не сдвигаются +1

    private $_category;
    private $_manufacture;
    private $_without_price;
    private $titles = [
        'A' => 'ID',                //      /\
        'B' => 'Производитель',     //     /||\
        'C' => 'Название',          //    //||\\
        'D' => 'Маркировка',        //      ||
        'E' => 'Комплектация',      //      ||
        'F' => 'Обивка',            //      ||
        'G' => 'Цена',
        'H' => 'Цена со скидкой',
        'I' => 'Процент скидки',
        'J' => 'Сумма скидки',
        'K' => 'ИТОГ',
        'L' => 'Наличие',
        'M' => 'Кол-во дней',
        'N' => 'Комментарий',
        'O' => '--',
        'P' => '--',
    ];

    public function init($category, $manufacture, $without_price){
        ini_set('max_execution_time', 100);
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
                    if(!$this->param_name || !$this->param_name_second){
                        if($param) {
                            $name = $param->productPriceParam->name;
                            $this->param_name = $this->param_name ? $this->param_name : (
                                $this->param_name_second !== $name ? $name : null
                            );
                            $this->param_name_second = $this->param_name_second ? $this->param_name_second : (
                                $this->param_name !== $name ? $name : null
                            );
                        } elseif($paramSecond){
                            $name = $param->productPriceParam->name;
                            $this->param_name = $this->param_name ? $this->param_name : (
                                $this->param_name_second !== $name ? $name : null
                            );
                            $this->param_name_second = $this->param_name_second ? $this->param_name_second : (
                                $this->param_name !== $name ? $name : null
                            );
                        }
                    }
                    $modify = $weight = '';
                    if($param && $param->productPriceParam->name == $this->param_name) {
                        $modify = $param->name;
                    } elseif($paramSecond && $paramSecond->productPriceParam->name == $this->param_name) {
                        $modify =  $paramSecond->name;
                    }
                    if($param && $param->productPriceParam->name == $this->param_name_second) {
                        $weight = $param->name;
                    } elseif($paramSecond && $paramSecond->productPriceParam->name == $this->param_name_second) {
                        $weight = $paramSecond->name;
                    }
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
                        $modify,
                        $weight,
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
        array_multisort(array_column($array, 16),  SORT_DESC,
            array_column($array, 0),  SORT_DESC,
            array_column($array, 10), SORT_ASC,
            array_column($array, 17), SORT_ASC,
            array_column($array, 4), SORT_ASC,
            $array);
        foreach ($array as $key => $value) {
            $num = $key + 2;
            $array[$key][self::FORMULA] = "= IF(ISBLANK(" . $this->offsetLeter('H'). "$num), IF(ISBLANK("
                . $this->offsetLeter('I'). "$num),  "
                . $this->offsetLeter('G'). "$num-"
                . $this->offsetLeter('J'). "$num, "
                . $this->offsetLeter('G'). "$num * ((100-"
                . $this->offsetLeter('I'). "$num)/100)), "
                . $this->offsetLeter('H'). "$num)";
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
        $this->_sheet->getStyle($this->offsetLeter('G') . '1:' . $this->offsetLeter('G') . '9999')->applyFromArray($E);
        $this->_sheet->getStyle($this->offsetLeter('K') . '1:' . $this->offsetLeter('K') . '9999')->applyFromArray($I);
        $this->_sheet->getStyle($this->offsetLeter('H') . '1:' . $this->offsetLeter('J') . '9999')->applyFromArray($F);
        $this->_sheet->getStyle($this->offsetLeter('O') . '1:' . $this->offsetLeter('P') . '9999')->applyFromArray($M);
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
            'G' => 15,
            'H' => 15,
            'I' => 15,
            'J' => 15,
            'K' => 13,
            'L' => 12,
            'M' => 12,
            'N' => 50,
            'O' => 0.01,
            'P' => 0.01
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

        $this->_sheet->getProtection()->setPassword('AaH4nv*j4j');
        $this->_sheet->getStyle($this->offsetLeter('G') . '2:' . $this->offsetLeter('J') . '9999')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $this->_sheet->getStyle($this->offsetLeter('K') . '2:'. $this->offsetLeter('N') . '9999')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        return $this;
    }

    private function setExcelColumnTitles(){
        $this->titles['E'] = $this->param_name;
        $this->titles['F'] = $this->param_name_second;
        foreach ($this->titles as $key => $title) {
            $this->_sheet->setCellValue($this->offsetLeter($key)."1", $title);
        }
        return $this;
    }

    private function setExcelCellValues($array){
        $x = 2;
        foreach ($array as $item) {
            foreach(range('A', 'P') as $key => $columnID) {
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
        $filename = date('Y-m-d') . "_" . rand(99, 879) . '_belmebel_export.xlsx';
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
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'
        ];
    }

    private function getOffset(){
        if(!$this->param_name && !$this->param_name_second){
            $offset = 2;
        } elseif(($this->param_name && !$this->param_name_second) || (!$this->param_name && $this->param_name_second)) {
            $offset = 1;
        } else {
            $offset = 0;
        }
        return $offset;
    }
}

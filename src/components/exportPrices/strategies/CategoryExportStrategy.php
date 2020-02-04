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
    /**
     * @var Spreadsheet
     */
    private $_spreadsheet;
    /**
     * @var Worksheet
     */
    private $_sheet;

    const STOCK = 'J';

    private $_category;
    private $_titles = [
        'A' => 'ID',
        'B' => 'Название',
        'C' => 'Комплектация',
        'D' => 'Обивка',
        'E' => 'Цена',
        'F' => 'Цена со скидкой',
        'G' => 'Процент скидки',
        'H' => 'Сумма скидки',
        'I' => 'ИТОГ',
        'J' => 'Наличие',
        'K' => 'Кол-во дней',
        'L' => 'Комментарий',
        'M' => '--',
        'N' => '--',
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
                        ($product->id * ($_productPrice->id + 3) ), // N
                        $product->popular_weight
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
                    null, //M
                    null, // N
                    $product->popular_weight
                ];
            }
        }
        array_multisort(array_column($array, 14),  SORT_DESC,
            array_column($array, 2), SORT_ASC,
            array_column($array, 8), SORT_ASC,
            $array);
        foreach ($array as $key => $value) {
            $num = $key + 2;
            $array[$key][8] = "= IF(ISBLANK(F$num), IF(ISBLANK(G$num),  E$num-H$num, E$num * ((100-G$num)/100)), F$num)";
//                        "= ЕСЛИ(ЕПУСТО(F$num); ЕСЛИ(ЕПУСТО(G$num);  E$num-H$num; E117 * ((100-G$num)/100)); F$num)"
        }
        $this->createExelFile($array);
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
        $this->_sheet->getStyle('A1:A9999')->applyFromArray($A);
        $this->_sheet->getStyle('E1:E9999')->applyFromArray($E);
        $this->_sheet->getStyle('I1:I9999')->applyFromArray($I);
        $this->_sheet->getStyle('F1:H9999')->applyFromArray($F);
        $this->_sheet->getStyle('M1:N9999')->applyFromArray($M);
        return $this;
    }

    private function setExcelColumnSizes(){
        $sizes = [
            'A' => 5,
            'B' => 25,
            'C' => 20,
            'D' => 15,
            'F' => 15,
            'G' => 15,
            'H' => 13,
            'J' => 12,
            'K' => 12,
            'L' => 50,
            'M' => 0.01,
            'N' => 0.01,
        ];
        foreach ($sizes as $key => $size) {
            $this->_sheet->getColumnDimension($key)->setWidth($size);
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
        $this->_sheet->getStyle('E2:H9999')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        $this->_sheet->getStyle('J2:L9999')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
        return $this;
    }

    private function setExcelColumnTitles(){
        foreach ($this->_titles as $key => $title) {
            $this->_sheet->setCellValue("{$key}1", $title);
        }
        return $this;
    }

    private function setExcelCellValues($array){
        $x = 2;
        foreach ($array as $item) {
            foreach(range('A', 'N') as $key => $columnID) {
                if(array_key_exists($key, $item)) {
                    $this->_sheet->setCellValue("$columnID".$x, $item[$key]);
                    if($columnID == self::STOCK) {
                        $this->setExelSelect($this->_sheet->getCell("$columnID".$x));
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
        $filename = date('Y-m-d H:i') . '_export.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer->save("php://output");
    }
}

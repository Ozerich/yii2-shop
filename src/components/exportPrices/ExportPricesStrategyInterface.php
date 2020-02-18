<?php

namespace ozerich\shop\components\exportPrices;

interface ExportPricesStrategyInterface
{
    public function init($params, $filename, $category, $manufacture, $without_price);

    public function export();
}

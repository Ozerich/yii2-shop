<?php

namespace ozerich\shop\components\exportPrices;

interface ExportPricesStrategyInterface
{
    public function init($model, $manufacture, $price);

    public function export();
}

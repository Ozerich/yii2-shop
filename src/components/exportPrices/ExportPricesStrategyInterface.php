<?php

namespace ozerich\shop\components\exportPrices;

interface ExportPricesStrategyInterface
{
    public function init($model);

    public function export();
}

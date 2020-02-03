<?php

namespace ozerich\shop\components\importPrices;

interface ImportPricesStrategyInterface
{
    public function init($file);

    public function import();
}

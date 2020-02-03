<?php


namespace ozerich\shop\components\exportPrices;


class ExportPrices
{
    /** @var ExportPricesStrategyInterface $strategy */
    private $strategy;

    /**
     * Context constructor.
     * @param  ExportPricesStrategyInterface  $strategy
     */
    public function __construct(ExportPricesStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function run($model)
    {
        $this->strategy->init($model);
        return $this->strategy->export();
    }
}

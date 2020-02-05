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

    public function run($model, $manufacture, $without_price)
    {
        $this->strategy->init($model, $manufacture, $without_price);
        return $this->strategy->export();
    }
}

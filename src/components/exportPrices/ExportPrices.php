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

    /**
     * @param $params
     * @param $filename
     * @param $category
     * @param $manufacture
     * @param $without_price
     * @return mixed
     */
    public function run($params, $filename, $category, $manufacture, $without_price)
    {
        $this->strategy->init($params, $filename, $category, $manufacture, $without_price);
        return $this->strategy->export();
    }
}

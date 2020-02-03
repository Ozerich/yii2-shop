<?php


namespace ozerich\shop\components\importPrices;


class ImportPrices
{
    /** @var ImportPricesStrategyInterface $strategy */
    private $strategy;

    /**
     * Context constructor.
     * @param  ImportPricesStrategyInterface  $strategy
     */
    public function __construct(ImportPricesStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function run($file)
    {
        $this->strategy->init($file);
        return $this->strategy->import();
    }
}

<?php

namespace ozerich\shop\import;

interface ImportProductStrategyInterface
{
    public function domains();

    public function manufacture();

    /**
     * @param $url
     * @return ImportProduct
     */
    public function import($url);
}
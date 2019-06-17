<?php

namespace ozerich\shop\plugins;

abstract class BasePlugin
{
    abstract function bootstrap();

    protected function registerProductTab(IProductTab $tab)
    {
        ProductTabsStorage::register($tab);
    }
}
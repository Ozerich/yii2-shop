<?php

namespace ozerich\shop\plugins;

use ReflectionClass;
use yii\console\Application;

abstract class BasePlugin implements IPlugin
{
    public function __construct()
    {
        if (\Yii::$app instanceof Application) {
            $this->initializeMigrations();
        }
    }

    private function initializeMigrations()
    {
        $className = get_called_class();

        $reflection = new ReflectionClass($className);
        $dir = dirname($reflection->getFileName());

        $migrationsDir = $dir . '/migrations';
        if (!file_exists($migrationsDir)) {
            return;
        }

        \Yii::$app->controllerMap['migrate']['migrationNamespaces'][] = $reflection->getNamespaceName() . '\migrations';
    }

    protected function registerProductTab(IProductTab $tab)
    {
        ProductTabsStorage::register($tab);
    }

    protected function registerAction($action, $actionHandlerClass)
    {
        ActionsStorage::register($this, $action, $actionHandlerClass);
    }
}
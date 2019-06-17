<?php

namespace ozerich\shop\plugins;

use yii\base\Action;

class ActionsStorage
{
    /** @var string[] */
    public static $actions = [];

    static function register(BasePlugin $plugin, $action, $actionHandlerClass)
    {
        if (!isset(self::$actions[$plugin->id()])) {
            self::$actions[$plugin->id()] = [];
        }

        self::$actions[$plugin->id()][$action] = $actionHandlerClass;
    }

    /**
     * @param $action
     * @return Action|null
     * @throws \yii\base\InvalidConfigException
     */
    static function get($pluginId, $action)
    {
        if (!isset(self::$actions[$pluginId][$action])) {
            return null;
        }

        $actionClass = self::$actions[$pluginId][$action];

        $result = \Yii::createObject($actionClass, [
            $action,
            \Yii::$app->controller
        ]);

        if ($result instanceof Action == false) {
            return null;
        }

        return $result;
    }
}
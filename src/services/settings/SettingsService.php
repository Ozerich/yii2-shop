<?php

namespace ozerich\shop\services\settings;

use ozerich\shop\models\Settings;

class SettingsService
{
    public function get($param, $default = null)
    {
        /** @var Settings $model */

        $model = Settings::findByOption($param)->one();
        if (!$model) {
            return $default;
        }

        return $model->value;
    }

    public function set($param, $value)
    {
        $model = Settings::findByOption($param)->one();

        if (!$model) {
            $model = new Settings();
            $model->option = $param;
        }

        $model->value = $value;
        $model->save();
    }
}

<?php

namespace ozerich\shop\services\settings;

use ozerich\shop\constants\SettingValueType;
use ozerich\shop\models\Settings;

class SettingsService
{
    public function get($param, $default = null, $type = null)
    {
        /** @var Settings $model */
        $model = Settings::findByOption($param)->one();
        if (!$model) {
            return $default;
        }

        $value = $model->value;

        switch ($type) {
            case SettingValueType::BOOLEAN:
                return $value === '1' || $value === true;
            case SettingValueType::INTEGER:
                return (int)$value;
            default:
                return $value;
        }
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

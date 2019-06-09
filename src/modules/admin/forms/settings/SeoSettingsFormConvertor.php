<?php

namespace ozerich\shop\modules\admin\forms\settings;

use ozerich\shop\constants\SettingOption;
use ozerich\shop\traits\ServicesTrait;
use yii\base\Model;

class SeoSettingsFormConvertor extends Model
{
    use  ServicesTrait;

    public function loadForm()
    {
        $form = new SeoSettingsForm();

        $form->products_title_template = $this->settingsService()->get(SettingOption::SEO_TITLE_TEMPLATE);
        $form->products_description_template = $this->settingsService()->get(SettingOption::SEO_DESCRIPTION_TEMPLATE);

        return $form;
    }

    public function saveModelFromForm(SeoSettingsForm $form)
    {
        $this->settingsService()->set(SettingOption::SEO_TITLE_TEMPLATE, $form->products_title_template);
        $this->settingsService()->set(SettingOption::SEO_DESCRIPTION_TEMPLATE, $form->products_description_template);
    }
}
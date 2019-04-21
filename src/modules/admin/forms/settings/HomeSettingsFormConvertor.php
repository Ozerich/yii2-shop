<?php

namespace ozerich\shop\modules\admin\forms\settings;

use ozerich\shop\constants\SettingOption;
use ozerich\shop\traits\ServicesTrait;
use yii\base\Model;

class HomeSettingsFormConvertor extends Model
{
    use  ServicesTrait;

    public function loadForm()
    {
        $form = new HomeSettingsForm();

        $form->page_title = $this->settingsService()->get(SettingOption::HOME_TITLE);
        $form->meta_description = $this->settingsService()->get(SettingOption::HOME_DESCRIPTION);
        $form->meta_image_id = $this->settingsService()->get(SettingOption::HOME_IMAGE_ID);
        $form->content = $this->settingsService()->get(SettingOption::HOME_CONTENT);

        return $form;
    }

    public function saveModelFromForm(HomeSettingsForm $form)
    {
        $this->settingsService()->set(SettingOption::HOME_TITLE, $form->page_title);
        $this->settingsService()->set(SettingOption::HOME_DESCRIPTION, $form->meta_description);
        $this->settingsService()->set(SettingOption::HOME_IMAGE_ID, $form->meta_image_id);
        $this->settingsService()->set(SettingOption::HOME_CONTENT, $form->content);
    }
}
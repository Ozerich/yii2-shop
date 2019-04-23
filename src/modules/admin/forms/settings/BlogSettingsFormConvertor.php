<?php

namespace ozerich\shop\modules\admin\forms\settings;

use ozerich\shop\constants\SettingOption;
use ozerich\shop\constants\SettingValueType;
use ozerich\shop\traits\ServicesTrait;
use yii\base\Model;

class BlogSettingsFormConvertor extends Model
{
    use  ServicesTrait;

    public function loadForm()
    {
        $form = new BlogSettingsForm();

        $form->page_title = $this->settingsService()->get(SettingOption::BLOG_TITLE);
        $form->meta_description = $this->settingsService()->get(SettingOption::BLOG_DESCRIPTION);
        $form->meta_image_id = $this->settingsService()->get(SettingOption::BLOG_IMAGE_ID);
        $form->enabled = $this->settingsService()->get(SettingOption::BLOG_ENABLED, false, SettingValueType::BOOLEAN);

        return $form;
    }

    public function saveModelFromForm(BlogSettingsForm $form)
    {
        $this->settingsService()->set(SettingOption::BLOG_ENABLED, $form->enabled);
        $this->settingsService()->set(SettingOption::BLOG_TITLE, $form->page_title);
        $this->settingsService()->set(SettingOption::BLOG_IMAGE_ID, $form->meta_image_id);
        $this->settingsService()->set(SettingOption::BLOG_DESCRIPTION, $form->meta_description);
    }
}
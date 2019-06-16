<?php

namespace ozerich\shop\import;

use ozerich\shop\models\Category;
use ozerich\shop\traits\ServicesTrait;
use yii\base\Model;

class ImportProductAdminForm extends Model
{
    use ServicesTrait;

    public $url;

    public $category_id;

    public function rules()
    {
        return [
            [['url', 'category_id'], 'required'],
            [['url'], 'url'],
            [['url'], 'validateUrl']
        ];
    }

    public function attributeLabels()
    {
        return [
            'url' => 'URL страницы с товаром на стороннем сайте',
            'category_id' => 'Категория'
        ];
    }

    public function validateUrl($attribute)
    {
        $value = $this->$attribute;

        if (!$this->importProductService()->validateUrl($value)) {
            $this->addError($attribute, 'Неверный URL');
        }
    }

    /**
     * @return Category|null
     */
    public function getCategory()
    {
        return Category::findOne($this->category_id);
    }
}
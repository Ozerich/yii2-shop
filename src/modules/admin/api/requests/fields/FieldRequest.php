<?php

namespace ozerich\shop\modules\admin\api\requests\fields;

use ozerich\api\request\RequestModel;

class FieldRequest extends RequestModel
{
    public $name;

    public $type;

    public $value_suffix;

    public $value_prefix;

    public $values;

    public $yes_label;

    public $no_label;

    public $multiple;

    public $filter_enabled;

    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['multiple', 'filter_enabled'], 'boolean'],
            [['value_suffix', 'value_prefix', 'yes_label', 'no_label'], 'string'],
            ['values', 'safe']
        ];
    }
}
<?php

namespace ozerich\shop\modules\api\requests\category;

use ozerich\api\request\RequestModel;

class ImportRequest extends RequestModel
{
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file'],
            [['file'], 'required'],
        ];
    }
}

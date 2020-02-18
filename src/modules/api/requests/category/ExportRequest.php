<?php

namespace ozerich\shop\modules\api\requests\category;

use ozerich\api\request\RequestModel;

class ExportRequest extends RequestModel
{
    public $params;
    public $filename;

    public function rules()
    {
        return [
            [[ 'filename'], 'required'],
            [['filename'], 'string'],
            ['params',  'each', 'rule' => ['string']],
        ];
    }
}

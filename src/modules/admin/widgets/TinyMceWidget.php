<?php

namespace ozerich\shop\modules\admin\widgets;

use ozerich\admin\widgets\TinyMce;

class TinyMceWidget extends TinyMce
{
    public $enabledImagesUpload = true;

    public $imagesUploadUrl = '/admin/default/upload-tinymce';
}
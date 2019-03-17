<?php

namespace ozerich\shop\modules\admin\widgets;

class ImageWidget extends \ozerich\filestorage\widgets\ImageWidget
{
    public $uploadUrl = '/admin/default/upload';

    public $scenario = 'default';
}
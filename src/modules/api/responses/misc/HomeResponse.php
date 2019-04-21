<?php

namespace ozerich\shop\modules\api\responses\misc;

use ozerich\api\response\BaseResponse;
use ozerich\shop\models\Image;

class HomeResponse extends BaseResponse
{
    private $title;

    private $description;

    /** @var Image */
    private $ogImage;

    private $seoContent;

    public function setContent($value)
    {
        $this->seoContent = $value;
    }

    public function setSeoParams($title, $description, ?Image $image = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->ogImage = $image;
    }

    public function toJson()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->ogImage ? $this->ogImage->getUrl('og') : null,
            'content' => $this->content
        ];
    }
}


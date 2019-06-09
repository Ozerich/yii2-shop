<?php

namespace ozerich\shop\constants;

use MyCLabs\Enum\Enum;

class SettingOption extends Enum
{
    const HOME_TITLE = 'home_title';
    const HOME_DESCRIPTION = 'home_description';
    const HOME_IMAGE_ID = 'home_image_id';
    const HOME_CONTENT = 'home_content';

    const BLOG_ENABLED = 'blog_enabled';
    const BLOG_TITLE = 'blog_title';
    const BLOG_DESCRIPTION = 'blog_description';
    const BLOG_IMAGE_ID = 'blog_image_id';

    const SEO_TITLE_TEMPLATE = 'seo_title_template';
    const SEO_DESCRIPTION_TEMPLATE = 'seo_description_template';
}
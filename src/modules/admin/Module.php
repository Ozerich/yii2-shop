<?php

namespace ozerich\shop\modules\admin;

use ozerich\shop\constants\SettingOption;
use ozerich\shop\constants\SettingValueType;
use ozerich\shop\traits\ServicesTrait;

class Module extends \ozerich\admin\Module
{
    use ServicesTrait;

    public $userIdentityClass = 'ozerich\shop\modules\admin\models\AdminUser';

    public $shortName = 'YS';

    public $fullName = 'Yii2 Shop';

    public $isBoxedLayout = false;

    public $logoUrl = '/';

    public $loginDuration = 99999999;

    public $menu = [
        [
            'id' => 'categories',
            'link' => '/categories',
            'label' => 'Категории',
            'icon' => 'book'
        ],
        [
            'id' => 'products',
            'link' => '/products',
            'label' => 'Товары',
            'icon' => 'book',
            'submenu' => [
                [
                    'id' => 'posts',
                    'label' => 'Все товары',
                    'link' => '/products',
                ],
                [
                    'id' => 'categories',
                    'label' => 'Спрятанные товары',
                    'link' => '/products/?FilterProduct[hidden]=1',
                ],
                [
                    'id' => 'product-params',
                    'label' => 'Характеристики',
                    'link' => '/products/params',
                ],
            ]
        ],
        [
            'id' => 'prices',
            'link' => '/products/prices',
            'label' => 'Цены и наличие',
            'icon' => 'book'
        ],
        [
            'id' => 'collections',
            'link' => '/collections',
            'label' => 'Коллекции',
            'icon' => 'book'
        ],
        [
            'id' => 'manufactures',
            'link' => '/manufactures',
            'label' => 'Производители',
            'icon' => 'book'
        ],
        [
            'id' => 'pages',
            'link' => '/pages',
            'label' => 'Страницы',
            'icon' => 'book'
        ],
        [
            'id' => 'blog',
            'link' => '/blog',
            'label' => 'Блог',
            'icon' => 'book',
            'submenu' => [
                [
                    'id' => 'posts',
                    'label' => 'Посты',
                    'link' => '/blog/posts',
                ],
                [
                    'id' => 'categories',
                    'label' => 'Категории',
                    'link' => '/blog/categories',
                ],
            ]
        ],
        [
            'id' => 'menu',
            'link' => '/menu/1',
            'label' => 'Меню',
            'icon' => 'book',
            'submenu' => [
                [
                    'id' => 'menu-top',
                    'label' => 'Верхнее меню',
                    'link' => '/menu/1',
                ],
                [
                    'id' => 'menu-bottom',
                    'label' => 'Нижнее меню',
                    'link' => '/menu/2',
                ],
            ]
        ],
        [
            'id' => 'settings',
            'link' => '#',
            'label' => 'Настройки',
            'icon' => 'book',
            'submenu' => [
                [
                    'id' => 'settings-home',
                    'label' => 'Главная страница',
                    'link' => '/settings/home',
                ],
                [
                    'id' => 'blog',
                    'label' => 'Блог',
                    'link' => '/settings/blog',
                ],
                [
                    'id' => 'blog',
                    'label' => 'Валюты',
                    'link' => '/settings/currencies',
                ],
                [
                    'id' => 'colors',
                    'label' => 'Цвета',
                    'link' => '/settings/colors',
                ],
            ]
        ],
    ];

    public function init()
    {
        $blogEnabled = $this->settingsService()->get(SettingOption::BLOG_ENABLED, false, SettingValueType::BOOLEAN);

        if (!$blogEnabled) {
            $this->menu = array_filter($this->menu, function ($item) {
                return $item['id'] != 'blog';
            });
        }

        parent::init();
    }
}
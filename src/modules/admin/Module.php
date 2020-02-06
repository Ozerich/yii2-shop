<?php

namespace ozerich\shop\modules\admin;

use ozerich\shop\constants\SettingOption;
use ozerich\shop\constants\SettingValueType;
use ozerich\shop\plugins\PagesStorage;
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

    public $importProductStrategies = [];

    public $menu = [];

    private function prepareMenu($coreMenu)
    {
        $menu = $coreMenu;

        $pluginsMenu = PagesStorage::getMenuPages();

        foreach ($pluginsMenu as $menuItem) {
            if (!$menuItem['parent']) {
                $menu[] = [
                    'id' => $menuItem['id'],
                    'link' => $menuItem['url'],
                    'label' => $menuItem['label'],
                    'icon' => 'book'
                ];
            } else {
                foreach ($menu as &$menuIter) {
                    if ($menuIter['id'] == $menuItem['parent']) {
                        if (!isset($menuIter['submenu'])) {
                            $menuIter['submenu'] = [];
                        }
                        $menuIter['submenu'][] = [
                            'id' => $menuItem['id'],
                            'link' => $menuItem['url'],
                            'label' => $menuItem['label'],
                        ];
                        break;
                    }
                }
            }
        }

        $this->menu = $menu;
    }

    public function init()
    {
        $blogEnabled = $this->settingsService()->get(SettingOption::BLOG_ENABLED, false, SettingValueType::BOOLEAN);

        if (!$blogEnabled) {
            $this->menu = array_filter($this->menu, function ($item) {
                return $item['id'] != 'blog';
            });
        }

        $this->prepareMenu(
            [
                [
                    'id' => 'categories',
                    'link' => '/categories',
                    'label' => 'Категории',
                    'icon' => 'book',
                    'submenu' => [
                        [
                            'id' => 'posts',
                            'label' => 'Категории',
                            'link' => '/categories',
                        ],
                        [
                            'id' => 'posts',
                            'label' => 'SEO теги',
                            'link' => '/categories/seo',
                        ]
                    ]
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
                        [
                            'id' => 'product-colors',
                            'label' => 'Цвета',
                            'link' => '/products/colors',
                        ],
                        [
                            'id' => 'import',
                            'label' => 'Импорт по URL',
                            'link' => '/products/import-by-url'
                        ],
                    ]
                ],
                [
                    'id' => 'product-prices',
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
                    'id' => 'banner',
                    'label' => 'Баннеры',
                    'link' => '/banner/',
                    'icon' => 'book',
                    'submenu' => [
                        [
                            'id' => 'banners',
                            'label' => 'Баннеры',
                            'link' => '/banner/',
                        ],
                        [
                            'id' => 'banner-areas',
                            'label' => 'Зоны',
                            'link' => '/banner-areas/',
                        ]
                    ]
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
                    'id' => 'export',
                    'label' => 'Экспорт',
                    'link' => '/export/category',
                    'icon' => 'book',
                ],
                [
                    'id' => 'import',
                    'label' => 'Импорт',
                    'link' => '/import/category',
                    'icon' => 'book',
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
                        [
                            'id' => 'seo',
                            'label' => 'SEO настройки',
                            'link' => '/settings/seo',
                        ],
                    ]
                ],
            ]
        );

        parent::init();
    }
}

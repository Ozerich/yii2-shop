<?php

namespace ozerich\shop\modules\admin;

class Module extends \ozerich\admin\Module
{
    public $userIdentityClass = 'app\modules\admin\models\AdminUser';

    public $shortName = 'BM';

    public $fullName = 'Belmebel';

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
            'icon' => 'book'
        ],
        [
            'id' => 'fields',
            'link' => '/fields',
            'label' => 'Поля',
            'icon' => 'book',
            'submenu' => [
                [
                    'id' => 'fields',
                    'link' => '/fields',
                    'label' => 'Поля',
                ],
                [
                    'id' => 'fields',
                    'link' => '/fields/groups',
                    'label' => 'Группы полей',
                ],
            ]
        ],
        [
            'id' => 'pages',
            'link' => '/pages',
            'label' => 'Страницы',
            'icon' => 'book'
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
    ];
}
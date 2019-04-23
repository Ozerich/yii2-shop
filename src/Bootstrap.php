<?php

namespace ozerich\shop;

use ozerich\shop\components\Google\Spreadsheets\GoogleSpreadsheets;
use ozerich\shop\components\Google\Spreadsheets\GoogleSpreadsheetsSync;
use ozerich\shop\constants\SettingOption;
use ozerich\shop\constants\SettingValueType;
use ozerich\shop\models\Category;
use ozerich\shop\models\Page;
use ozerich\shop\models\Product;
use ozerich\shop\traits\ServicesTrait;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    use ServicesTrait;

    private function bootstrapConsole(\yii\console\Application $app)
    {
        if (!isset($app->controllerMap['migrate'])) {
            $app->controllerMap['migrate'] = [
                'class' => 'yii\console\controllers\MigrateController',
                'migrationNamespaces' => [
                    'ozerich\shop\migrations',
                    'ozerich\filestorage\migrations',
                ],
            ];

            $app->setComponents([
                'spreadsheets' => [
                    'class' => GoogleSpreadsheets::class,
                    'credentials_file' => getenv('GOOGLE_SPREADSHEETS_CREDENTIALS_FILE')
                ],
                'spreadsheetsSync' => [
                    'class' => GoogleSpreadsheetsSync::class,
                    'spreadsheet_id' => getenv('GOOGLE_SPREADSHEET_ID')
                ],
                'cache' => [
                    'class' => 'yii\caching\FileCache',
                ],
            ]);
        }
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            return $this->bootstrapConsole($app);
        }

        $blogEnabled = $this->settingsService()->get(SettingOption::BLOG_ENABLED, false, SettingValueType::BOOLEAN);

        \Yii::$app->setModule('admin', [
            'class' => 'ozerich\shop\modules\admin\Module'
        ]);

        \Yii::$app->setModule('api', [
            'class' => 'ozerich\shop\modules\api\Module'
        ]);

        \Yii::$app->setModule('sitemap', [
            'class' => 'himiklab\sitemap\Sitemap',
            'models' => [
                Category::class,
                Product::class,
                Page::class
            ],
            'urls' => $blogEnabled ? [
                [
                    'loc' => '/blog',
                    'changefreq' => 'daily',
                    'priority' => 0.5,
                ]
            ] : []
        ]);

        $mediaConfig = [
            'class' => 'ozerich\filestorage\FileStorage',
            'modelClass' => 'ozerich\shop\models\Image',
            'scenarios' => [
                'default' => [
                    'storage' => [
                        'type' => 'file',
                        'uploadDirPath' => __DIR__ . '/../../../../web/uploads/default',
                        'uploadDirUrl' => '/uploads/default',
                    ],
                    'validator' => [
                        'maxSize' => 16 * 1024 * 1024,
                        'checkExtensionByMimeType' => true,
                        'extensions' => ['jpg', 'jpeg', 'bmp', 'gif', 'png']
                    ]
                ],
                'og' => [
                    'storage' => [
                        'type' => 'file',
                        'uploadDirPath' => __DIR__ . '/../../../../web/uploads/og',
                        'uploadDirUrl' => '/uploads/og',
                    ],
                    'validator' => [
                        'maxSize' => 16 * 1024 * 1024,
                        'checkExtensionByMimeType' => true,
                        'extensions' => ['jpg', 'jpeg', 'bmp', 'gif', 'png']
                    ],
                    'thumbnails' => [
                        [
                            'width' => 1200,
                            'height' => 630,
                            'crop' => true,
                            'alias' => 'main'
                        ],
                    ],
                ],
                'blog' => [
                    'storage' => [
                        'type' => 'file',
                        'uploadDirPath' => __DIR__ . '/../../../../web/uploads/blog',
                        'uploadDirUrl' => '/uploads/blog',
                    ],
                    'validator' => [
                        'maxSize' => 16 * 1024 * 1024,
                        'checkExtensionByMimeType' => true,
                        'extensions' => ['jpg', 'jpeg', 'bmp', 'gif', 'png']
                    ],
                    'thumbnails' => [
                        [
                            'width' => 1200,
                            'height' => 630,
                            'crop' => true,
                            'alias' => 'og'
                        ],
                        [
                            'width' => 540,
                            'height' => 300,
                            'crop' => true,
                            'alias' => 'preview'
                        ],
                    ],
                ],
                'field' => [
                    'storage' => [
                        'type' => 'file',
                        'uploadDirPath' => __DIR__ . '/../../../../web/uploads/fields',
                        'uploadDirUrl' => '/uploads/fields',
                    ],
                    'validator' => [
                        'maxSize' => 16 * 1024 * 1024,
                        'checkExtensionByMimeType' => true,
                        'extensions' => ['jpg', 'jpeg', 'bmp', 'gif', 'png']
                    ]
                ],
                'product' => [
                    'storage' => [
                        'type' => 'file',
                        'uploadDirPath' => __DIR__ . '/../../../../web/uploads/products',
                        'uploadDirUrl' => '/uploads/products',
                    ],
                    'validator' => [
                        'maxSize' => 16 * 1024 * 1024,
                        'checkExtensionByMimeType' => true,
                        'extensions' => ['jpg', 'jpeg', 'bmp', 'gif', 'png']
                    ],
                    'thumbnails' => [
                        [
                            'alias' => 'preview',
                            'width' => 205,
                            'height' => 100
                        ],
                        [
                            'alias' => 'gallery-preview',
                            'width' => 110,
                            'height' => 110
                        ],
                    ],
                ],
                'category' => [
                    'storage' => [
                        'type' => 'file',
                        'uploadDirPath' => __DIR__ . '/../../../../web/uploads/categories',
                        'uploadDirUrl' => '/uploads/categories',
                    ],
                    'validator' => [
                        'maxSize' => 16 * 1024 * 1024,
                        'checkExtensionByMimeType' => true,
                        'extensions' => ['jpg', 'jpeg', 'bmp', 'gif', 'png']
                    ],
                    'thumbnails' => [
                        [
                            'alias' => 'preview',
                            'width' => 338,
                            'height' => 190
                        ],
                    ],
                ],
            ]
        ];

        $app->setComponents([
            'media' => $mediaConfig,

            'cache' => [
                'class' => 'yii\caching\FileCache',
            ],

            'request' => [
                'class' => 'yii\web\Request',
                'baseUrl' => '',
                'enableCookieValidation' => false,
                'parsers' => [
                    'application/json' => 'yii\web\JsonParser'
                ]
            ],

            'response' => [
                'class' => 'yii\web\Response',
                'formatters' => [
                    'json' => [
                        'class' => 'yii\web\JsonResponseFormatter',
                        'prettyPrint' => false,
                        'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                    ],
                ],
            ],

            'urlManager' => [
                'class' => 'yii\web\UrlManager',
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'enableStrictParsing' => false,
                'rules' => [
                    '<module>/<controller>/<id:\d+>/<action>' => '<module>/<controller>/<action>',
                    '<module>/<controller>/<action>/<id:\d+>' => '<module>/<controller>/<action>',
                    '<controller>/<action>/<id:\d+>' => '<controller>/<action>',
                    '<controller>/<id:\d+>' => '<controller>/view',

                    ['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml']
                ]
            ],
        ]);

        $app->bootstrap = array_unique(array_merge($app->bootstrap ? $app->bootstrap : [], ['log', 'media']));
    }
}
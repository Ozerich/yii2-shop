<?php

namespace ozerich\shop;

use ozerich\shop\constants\SettingOption;
use ozerich\shop\constants\SettingValueType;
use ozerich\shop\models\Category;
use ozerich\shop\models\Page;
use ozerich\shop\models\Product;
use ozerich\shop\plugins\BasePlugin;
use ozerich\shop\traits\ServicesTrait;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public $importProductStrategies = [];

    public $plugins = [];

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
        }

        $this->bootstrapPlugins($app);
    }

    private function bootstrapPlugins($app)
    {
        foreach ($this->plugins as $pluginClass) {
            $plugin = \Yii::createObject($pluginClass);

            if ($plugin instanceof BasePlugin) {
                $plugin->bootstrap();
            }
        }
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            return $this->bootstrapConsole($app);
        }

        $blogEnabled = $this->settingsService()->get(SettingOption::BLOG_ENABLED, false, SettingValueType::BOOLEAN);

        \Yii::$app->setModule('admin', [
            'class' => 'ozerich\shop\modules\admin\Module',
            'importProductStrategies' => $this->importProductStrategies,
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
                        [
                            'width' => 350,
                            'height' => 200,
                            'crop' => true,
                            'alias' => 'small-preview'
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
                            'alias' => 'big-preview',
                            'width' => 850,
                            'height' => 350
                        ],
                        [
                            'width' => 1200,
                            'height' => 630,
                            'crop' => true,
                            'alias' => 'og'
                        ],
                        [
                            'width' => 1180,
                            'alias' => 'schema-preview'
                        ],
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
                'collection' => [
                    'storage' => [
                        'type' => 'file',
                        'uploadDirPath' => __DIR__ . '/../../../../web/uploads/collections',
                        'uploadDirUrl' => '/uploads/collections',
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
                            'alias' => 'preview-small',
                            'width' => 100,
                            'height' => 40
                        ],
                        [
                            'alias' => 'preview',
                            'width' => 205,
                            'height' => 100
                        ]
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
                        ], [
                            'alias' => 'small-preview',
                            'width' => 268,
                            'height' => 150
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
                    'admin/plugin/page/<plugin>/<alias>' => 'admin/plugin/page',
                    'admin/plugin/<plugin>/<action>' => 'admin/plugin/index',
                    '<module>/<controller>/<id:\d+>/<action>' => '<module>/<controller>/<action>',
                    '<module>/<controller>/<action>/<id:\d+>' => '<module>/<controller>/<action>',
                    '<controller>/<action>/<id:\d+>' => '<controller>/<action>',
                    '<controller>/<id:\d+>' => '<controller>/view',

                    ['pattern' => 'sitemap', 'route' => 'sitemap/default/index', 'suffix' => '.xml']
                ]
            ],
        ]);

        \Yii::createObject($mediaConfig);

        $this->bootstrapPlugins($app);
    }
}
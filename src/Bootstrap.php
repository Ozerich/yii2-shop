<?php

namespace ozerich\shop;

use yii\base\BootstrapInterface;


class Bootstrap implements BootstrapInterface
{
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
    }

    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            return $this->bootstrapConsole($app);
        }

        \Yii::$app->setModule('admin', [
            'class' => 'ozerich\shop\modules\admin\Module'
        ]);

        \Yii::$app->setModule('api', [
            'class' => 'ozerich\shop\modules\api\Module'
        ]);

        $mediaConfig = [
            'class' => 'ozerich\filestorage\FileStorage',
            'modelClass' => 'ozerich\shop\models\Image',
            'scenarios' => [
                'default' => [
                    'storage' => [
                        'type' => 'file',
                        'uploadDirPath' => __DIR__ . '/../../web/uploads/default',
                        'uploadDirUrl' => '/uploads/default',
                    ],
                    'validator' => [
                        'maxSize' => 16 * 1024 * 1024,
                        'checkExtensionByMimeType' => true,
                        'extensions' => ['jpg', 'jpeg', 'bmp', 'gif', 'png']
                    ]
                ],
                'field' => [
                    'storage' => [
                        'type' => 'file',
                        'uploadDirPath' => __DIR__ . '/../../web/uploads/fields',
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
                        'uploadDirPath' => __DIR__ . '/../../web/uploads/products',
                        'uploadDirUrl' => '/uploads/products',
                    ],
                    'validator' => [
                        'maxSize' => 16 * 1024 * 1024,
                        'checkExtensionByMimeType' => true,
                        'extensions' => ['jpg', 'jpeg', 'bmp', 'gif', 'png']
                    ]
                ],
                'category' => [
                    'storage' => [
                        'type' => 'file',
                        'uploadDirPath' => __DIR__ . '/../../web/uploads/categories',
                        'uploadDirUrl' => '/uploads/categories',
                    ],
                    'validator' => [
                        'maxSize' => 16 * 1024 * 1024,
                        'checkExtensionByMimeType' => true,
                        'extensions' => ['jpg', 'jpeg', 'bmp', 'gif', 'png']
                    ]
                ],
            ]
        ];

        $app->setComponents([
            'media' => $mediaConfig,

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
                ]
            ],
        ]);

        $app->bootstrap = array_unique(array_merge($app->bootstrap ? $app->bootstrap : [], ['log', 'media']));
    }
}
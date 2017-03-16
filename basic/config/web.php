<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'modules' => [
        'static-page-module' => [
            'class' => 'app\modules\StaticPageModule\StaticPageModule',
            
        ]
    ],
    /*'container' => [
        'definitions' => [
            'app\modules\StaticPageModule\repositories\abstractions\ICategoryRepository' => 'app\modules\StaticPageModule\repositories\implementations\CategoryRepository',
            'app\modules\StaticPageModule\repositories\abstractions\IRatingItemRepository' => 'app\modules\StaticPageModule\repositories\implementations\RatingItemRepository',
            'app\modules\StaticPageModule\repositories\abstractions\IStaticPageRepository' => 'app\modules\StaticPageModule\repositories\implementations\StaticPageRepository',
            'app\modules\StaticPageModule\services\abstractions\ICategoryService' => 'app\modules\StaticPageModule\services\implementations\CategoryService',
            'app\modules\StaticPageModule\services\abstractions\IRatingItemService' => 'app\modules\StaticPageModule\services\implementations\RatingItemService',
            'app\modules\StaticPageModule\repositories\abstractions\IStaticPageRepository' => 'app\modules\StaticPageModule\services\implementations\StaticPageService'
            
        ]
    ],*/
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'TooSecretKeyForMice',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],      
        'db' => require(__DIR__ . '/db.php'),
        
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
           'enableStrictParsing' => false,
            //'encodeParams' => false,
            'showScriptName' => false,
            'suffix' => '.html',
            'rules' => [
                'page/<slug:\\w+>' => 'static-page-module/static-page/get-page',
                'page/category/<slug:\\w+>/<page:\d+>' => 'static-page-module/category/get-category',
                'page/tag/<tag:\\w+>/<page:\d+>' => 'static-page-module/static-page/get-pages-by-tag',
                'create/page' => 'static-page-module/static-page/create-page',
                'update/page/<slug:\\w+>' => 'static-page-module/static-page/update-page',
                'create/category' => 'static-page-module/category/create-category',
                'admin-page' => 'static-page-module/static-page/admin-page',
                'user-page/<author:\\w+>' => 'static-page-module/static-page/user-page',
                'delete/page/<slug:\\w+>' => 'static-page-module/static-page/delete-page',
                'null-rating/page' => 'static-page-module/static-page/null-rating',
                'add-rating' => 'static-page-module/static-page/add-rating'
            ],
        ],
        
    ],
    
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;

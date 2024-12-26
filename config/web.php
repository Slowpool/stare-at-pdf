<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Home', // let it be
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        
        '@views' => '@app/views',
        '@main_layout' => '@views/layouts/main.php',
        
        '@home_view' => '//viewer/index.php',
        '@login_view' => '//identity/login.php',
        '@library_view' => '//library/index.php',
        
        '@partial_nav_logout_form' => '//layouts/partial_nav_logout_form.php',
        '@partial_nav_login_button' => '//layouts/partial_nav_login.php',
        '@partial_new_file_form' => '//library/partial_new_file_form.php',
        '@partial_pdf_card' => '//library/partial_pdf_card.php',
        '@partial_new_bookmark_form' => '//viewer/partial_new_bookmark_form.php',
        '@uploads' => '@app/web/uploads',
        '@scripts' => '@app/web/js',
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'Grx0TYPqpD-LAEbdgbayRShvE2FtzVkz',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => app\models\domain\UserRecord::class,
            'loginUrl' => '/login',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            // TODO not an action
            'errorAction' => 'viewer/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require_once 'urls.php',
        ],
        // 'headers' => [
        //     'class' => 'hyperia\security\Headers',
        //     'cspDirectives' => [
        //         'default-src' => "'self'",
        //         'script-src' => "'self'",
        //         'style-src' => "'self'",
        //         'img-src' => "'self'",
        //         'connect-src' => "'self'",
        //         'font-src' => "'self'",
        //         'object-src' => "'self'",
        //         'media-src' => "'self'",
        //         'form-action' => "'self'",
        //         'frame-src' => "'self'",
        //         'child-src' => "'self'"
        //     ]
        // ]
    ],
    'modules' => [
        'pdfjs' => [
            'class' => \diecoding\pdfjs\Module::class,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

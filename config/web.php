<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'Stare at pdf',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'headers'],
    'aliases' => [
        '@MAX_CATEGORY_NAME_LENGTH' => 50,
        '@CATEGORY_COLOR_LENGTH' => 6,
        // directories
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@views' => '@app/views',
        '@main_layout' => '@views/layouts/main.php',
        '@uploads' => '@app/web/uploads',
        '@scripts' => '@app/web/js',
        // viewer
        '@pdf_viewer_view' => '//viewer/index.php',
        '@error_view' => '//viewer/error.php',
        '@partial_new_bookmark_form' => '//viewer/partial_new_bookmark_form.php',
        // identity
        '@login_view' => '//identity/login.php',
        '@registration_view' => '//identity/login.php',
        '@partial_nav_logout_form' => '//identity/partial_nav_logout_form.php',
        '@partial_nav_login_button' => '//identity/partial_nav_unsigned_action.php',
        // library
        '@library_view' => '//library/index.php',
        '@partial_new_file_form' => '//library/partial_new_file_form.php',
        '@partial_pdf_card' => '//library/partial_pdf_card.php',
        '@partial_new_category_form' => '//library/partial_new_category_form.php',
        '@partial_assign_category_form' => '//library/partial_assign_category_form.php',
        
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
            // TODO not action
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
            'rules' => require 'urls.php',
        ],
        'headers' => require 'CSP.php',
    ],
    'modules' => [
        'pdfjs' => [
            'class' => \diecoding\pdfjs\Module::class,
        ],
    ],
    'params' => $params,
    // 'meta' => require 'meta.php', // UPD: deprecated thing
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

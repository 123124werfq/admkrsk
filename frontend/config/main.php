<?php

use common\models\Action;
use yii\web\UserEvent;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'api' => \frontend\modules\api\Module::class,
    ],
    'components' => [
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@frontend/assets/app',
                    'js' => [
                        'js/jquery-3.3.1.min.js',
                    ],
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,
                    'css' => [],
                ],
            ],
        ],
        'i18n' => array(
            'translations' => array(
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'ru',
                    //'on missingTranslation' => ['app\components\TranslationEventHandler', 'handleMissingTranslation']
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            )
        ),
        'request' => [
            'class' => 'common\base\Request',
            'csrfParam' => '_csrf-frontend',
            'trustedHosts' => [
                '10.24.0.0/24',
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
            ],
            'rules' => [
                'workflow/in'=>'workflow/in',
                'site/flush' => 'site/flush',
                [
                    'class' => 'yii\web\GroupUrlRule',
                    'prefix' => 'api',
                    'rules' => [
                        // collection
                        ['verb' => ['GET', 'OPTIONS'], 'pattern' => 'collection/<alias:\w+>', 'route' => 'collection/index'],
                        ['verb' => ['GET', 'OPTIONS'], 'pattern' => 'collection/<alias:\w+>/<id:\d+>', 'route' => 'collection/view'],
                        // news
                        ['verb' => ['GET', 'OPTIONS'], 'pattern' => 'news', 'route' => 'news/index'],
                        ['verb' => ['GET', 'OPTIONS'], 'pattern' => 'news/<id:\d+>', 'route' => 'news/view'],
                    ],
                ],
                [
                    'class' => 'frontend\components\LangUrlRule',
                ],
                'search/address'=>'search/address',
                'collection'=>'collection/view',
                'collection/word'=>'collection/word',
                'site/test'=>'site/test',
                'address/region'=>'address/region',
                'form/view/<id>'=>'form/view',
                'form/get-categories'=>'form/get-categories',
                'address/subregion'=>'address/subregion',
                'address/city'=>'address/city',
                'address/district'=>'address/district',
                'address/street'=>'address/street',
                'address/house'=>'address/house',
                'book/available' => 'book/available',
                'book/intervals' => 'book/intervals',
                /*[
                    'class' => 'frontend\components\PravoUrlRule',
                ],*/
                /*[
                    'class' => 'frontend\components\NewyearUrlRule',
                ],*/
                //'/' => 'site/page',
                'service/search'=>'service/search',
                '/press/events' => 'event/index',
                'subscribe' => 'subscribe/index',
                'un-subscribe' => 'subscribe/un-subscribe',
                'event/program' => 'event/program',
                'login' => 'site/login',
                'signup' => 'site/signup',
                'press/poll-list-active' => 'poll/index',
                'press/poll-list-active/poll-archive' => 'poll/archive',
                'press/poll-list-active/<id>' => 'poll/view',
                'opendata' => 'opendata/index',
                'opendata/<id>' => 'opendata/view',
                [
                    'class' => 'frontend\components\NewsUrlRule',
                ],
                //'contest/list/<id>' => 'contest/list',
                'contest/item/<id>' => 'contest/item',
                'contest/vote/<id>' => 'contest/vote'
                /*'<news|traffice|announce>'=>'news/index',
                '<news|traffice|a1nnounce>/<id>'=>'news/view',*/
                //'<alias:[.*]>'=>'site/page',
            ],

        ],
    ],
    'sourceLanguage' => 'ru',
    'language' => 'ru',

    'params' => $params,
];

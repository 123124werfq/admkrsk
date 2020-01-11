<?php
return [
    'language'=>'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'timeZone' => 'Asia/Krasnoyarsk',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'db' => [
            'schemaMap' => [
                'pgsql' => [
                    'class' => \yii\db\pgsql\Schema::class,
                    'columnSchemaClass' => [
                        'class' => \yii\db\pgsql\ColumnSchema::class,
                        'disableArraySupport' => false,
                        'deserializeArrayColumnToArrayExpression' => false,
                    ],
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => 'redisCache',
        ],
        'session' => [
            'class' => 'yii\redis\Session',
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'cache' => 'cache',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'class' => 'yii\web\User'
        ],
        'formatter' => [
            'timeZone' => 'Asia/Krasnoyarsk',
            'defaultTimeZone' => 'Asia/Krasnoyarsk',
            'dateFormat' => 'dd.MM.yyyy',
            'timeFormat' => 'HH:mm',
            'datetimeFormat' => 'dd.MM.yyyy HH:mm',
            'sizeFormatBase' => 1000,
        ],
        /*'queue' => [
            'class' => 'yii\queue\db\Queue',
            'db' => 'db',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => 'yii\mutex\PgsqlMutex',
        ],*/
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'esia' => [
                    'class' => 'heggi\yii2esia\Esia',
                    //'clientId' => '240501',
                    'clientId' => 'OSAK01241',
//                    'certPath' => __DIR__ .'/certificate.pem',
//                    'privateKeyPath' => __DIR__ .'/certificate.key',
                    'certPath' => __DIR__ .'/certificate.pem',
                    'privateKeyPath' => __DIR__ .'/certificate.key',
                    'privateKeyPassword' => '3gJaw9dNGp',
//                    'scope' => 'fullname birthdate gender snils inn id_doc birthplace medical_doc residence_doc email mobile contacts usr_org usr_avt org_shortname org_fullname org_type org_ogrn org_inn org_leg org_kpp org_ctts org_addrs',
                    'scope' => 'fullname birthdate mobile contacts snils inn id_doc birthplace medical_doc residence_doc email usr_org usr_avt', // FAILED: gender org_shortname и все орг
                    'production' => false,
                ],
            ],
        ],
        'ad' => [
            'class' => 'Edvlerblog\Adldap2\Adldap2Wrapper',

            // 'defaultProvider' => 'another_provider',
            'providers' => [
                'default' => [
                    'autoconnect' => true,

                    'config' => [
                        'account_suffix'        => '@admkrsk.ru',
                        'hosts'    => ['10.24.0.7'],
                        'base_dn'               => 'DC=admkrsk,DC=ru',
                        'username'        => 'web_user@admkrsk.ru',
                        'password'        => 'PaO5q#3ows',
                        //'port' => 636,
                        //'use_ssl' => true,
                        //'use_tls' => true,
                    ]
                ],
            ], // close providers array
        ], //close ad
    ],
];

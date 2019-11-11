<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=' . env('POSTGRES_HOST') . ';dbname=' . env('POSTGRES_DB'),
            'username' => env('POSTGRES_USER'),
            'password' => env('POSTGRES_PASSWORD'),
            'charset' => 'utf8',
        ],
        'logDb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'pgsql:host=' . env('POSTGRES_HOST') . ';dbname=' . env('POSTGRES_LOG_DB'),
            'username' => env('POSTGRES_USER'),
            'password' => env('POSTGRES_PASSWORD'),
            'charset' => 'utf8',
        ],
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://@' . env('MONGO_HOST') . ':' . env('MONGO_PORT') . '/' . env('MONGO_DB'),
            'options' => [
                'username' => env('MONGO_USER'),
                'password' => env('MONGO_PASSWORD'),
            ]
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => env('REDIS_HOST'),
            'port' => env('REDIS_PORT'),
            'database' => env('REDIS_DB'),
        ],
        'redisCache' => [
            'class' => 'yii\redis\Connection',
            'hostname' => env('REDIS_HOST'),
            'port' => env('REDIS_PORT'),
            'database' => env('REDIS_CACHE_DB'),
        ],
        'publicStorage' => [
            'class' => 'common\components\flysystem\AwsS3Filesystem',
            'credentials' => [
                'key'    => env('MINIO_ACCESS_KEY'),
                'secret' => env('MINIO_SECRET_KEY'),
            ],
            'bucket' => env('MINIO_BUCKET_PUBLIC'),
            'endpoint' => env('MINIO_ENDPOINT'),
        ],
        'privateStorage' => [
            'class' => 'common\components\flysystem\AwsS3Filesystem',
            'credentials' => [
                'key'    => env('MINIO_ACCESS_KEY'),
                'secret' => env('MINIO_SECRET_KEY'),
            ],
            'bucket' => env('MINIO_BUCKET_PRIVATE'),
            'endpoint' => env('MINIO_ENDPOINT'),
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];

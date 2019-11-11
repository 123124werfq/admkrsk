<?php
return [
    'components' => [
        'db' => [
            'dsn' => 'pgsql:host=' . env('POSTGRES_HOST') . ';dbname=' . env('POSTGRES_TEST_DB'),
        ],
    ],
];

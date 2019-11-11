<?php

require(__DIR__ . '/helpers.php');

$dotenv = Dotenv\Dotenv::create(dirname(__DIR__));
$dotenv->load();

defined('YII_DEBUG') or define('YII_DEBUG', env('YII_DEBUG', false));
defined('YII_ENV') or define('YII_ENV', env('YII_ENV', 'dev'));

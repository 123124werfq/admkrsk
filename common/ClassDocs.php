<?php

/**
 * Yii bootstrap file.
 * Used for enhanced IDE code autocompletion.
 * Note: To avoid "Multiple Implementations" PHPStorm warning and make autocomplete faster
 * exclude or "Mark as Plain Text" vendor/yiisoft/yii2/Yii.php file
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var BaseApplication|WebApplication|ConsoleApplication
     */
    public static $app;
}

/**
 * Класс для описания компонентов идентичных как для веб-приложения, так и для консольного приложения
 *
 * @property \yii\db\Connection $logDb
 * @property \yii\mongodb\Connection $mongodb
 * @property \yii\redis\Connection $redis
 * @property \common\components\flysystem\AwsS3Filesystem $storage
 * @property \common\components\flysystem\AwsS3Filesystem $publicStorage
 * @property \common\components\flysystem\AwsS3Filesystem $privateStorage
 */
abstract class BaseApplication extends yii\base\Application
{
}

/**
 * Класс для описания компонентов только веб-приложения
 *
 * @property User $user User component.
 */
class WebApplication extends yii\web\Application
{
}

/**
 * Класс для описания компонентов только консольного приложения
 */
class ConsoleApplication extends yii\console\Application
{
}

/**
 * Класс для описания компонента User
 *
 * @property \common\models\User $identity
 * @method \common\models\User getIdentity()
 */
class User extends \yii\web\User
{
}

<?php

namespace common\base;

use Yii;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\StringHelper;

class Job extends BaseObject
{
    /* @var string */
    static $path = '@console/runtime/queue';

    /* @var string */
    static $filename = '@console/runtime/queue/jobs.txt';

    /**
     * @return mixed|null
     */
    public static function getJobId()
    {
        $filename = Yii::getAlias(self::$filename);

        if (is_file($filename)) {
            $jobs = Json::decode(file_get_contents($filename));
        } else {
            $jobs = [];
        }

        return $jobs[StringHelper::basename(static::class)] ?? null;
    }

    /**
     * @param string|null $jobId
     * @throws Exception
     */
    public static function saveJobId(?string $jobId)
    {
        $path = Yii::getAlias(self::$path);
        $filename = Yii::getAlias(self::$filename);

        if (!is_dir($path)) {
            FileHelper::createDirectory($path);
        }

        if (is_file($filename)) {
            $jobs = Json::decode(file_get_contents($filename));
        } else {
            $jobs = [];
        }

        $jobs[StringHelper::basename(static::class)] = $jobId;

        file_put_contents($filename, Json::encode($jobs));
    }
}

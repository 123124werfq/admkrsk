<?php

namespace common\components\helper;

use yii\helpers\Json;

/**
 * Use this helper if you wanna decode base64 string from `query_string`
 */
class JsonHelper extends Json
{
    /**
     * If string become very long base64 will delimiter your parts `+`,
     * but when you got this string from http `query_string`,
     * she will contains spaces. Because content in `query_string` replace `+` on `{space}`
     *
     * @param string $str
     * @param bool $asArray
     * @return mixed
     */
    public static function decodeBase64(string $str, bool $asArray = true)
    {
        $parseString = strtr($str, '-_', '+/');
        return static::decode(base64_decode($parseString), $asArray);
    }
}
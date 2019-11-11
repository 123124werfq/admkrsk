<?php

namespace common\helpers;

use DateInterval;
use DateTime;
use Yii;
use yii\helpers\Console;

/**
 * @brief
 * ProgressHelper::startProgress($i, $count, "Start: ");
 * ProgressHelper::updateProgress(++$i, $count, null, true);
 * ProgressHelper::endProgress("100% ($count/$count) Done." . PHP_EOL);
 *
 * Class ProgressHelper
 * @package common\helpers
 */
class ProgressHelper extends Console
{
    private static $_progressStart;
    private static $_progressPrefix;
    private static $_progressEta;
    private static $_progressEtaLastDone = 0;
    private static $_progressEtaLastUpdate;
    private static $_progressEtaLastRate;

    /**
     * @inheritdoc
     */
    public static function startProgress($done, $total, $prefix = '', $width = null)
    {
        self::$_progressStart = time();
        self::$_progressPrefix = $prefix;
        self::$_progressEta = null;
        self::$_progressEtaLastDone = 0;
        self::$_progressEtaLastUpdate = time();

        static::updateProgress($done, $total);
    }

    /**
     * @inheritdoc
     */
    public static function updateProgress($done, $total, $prefix = null, $showMemory = false)
    {
        if ($prefix === null) {
            $prefix = self::$_progressPrefix;
        } else {
            self::$_progressPrefix = $prefix;
        }
        $percent = ($total == 0) ? 1 : $done / $total;
        $info = sprintf('%d%% (%d/%d)', $percent * 100, $done, $total);
        self::setETA($done, $total);
        $info .= self::$_progressEta === null ? ' ETA: n/a' : sprintf(' ETA: %s (%s)', self::asDuration(self::$_progressEta), self::getRate());
        if ($showMemory) {
            $info .= sprintf(' memory: %.3f MB', memory_get_usage() / 1048576);
        }

        static::clearLine();
        static::stdout("\r$prefix$info   ");
        flush();
    }

    /**
     * @inheritdoc
     */
    public static function endProgress($remove = false, $keepPrefix = true)
    {
        if ($remove === false) {
            static::stdout(PHP_EOL);
        } else {
            if (static::streamSupportsAnsiColors(STDOUT)) {
                static::clearLine();
            }
            static::stdout("\r" . ($keepPrefix ? self::$_progressPrefix : '') . (is_string($remove) ? $remove : ''));
        }
        flush();

        self::$_progressStart = null;
        self::$_progressPrefix = '';
        self::$_progressEta = null;
        self::$_progressEtaLastDone = 0;
        self::$_progressEtaLastUpdate = null;
        self::$_progressEtaLastRate = null;
    }

    /**
     * @inheritdoc
     */
    private static function setETA($done, $total)
    {
        if ($done > $total || $done == 0) {
            self::$_progressEta = null;
            self::$_progressEtaLastUpdate = time();
            return;
        }

        if ($done < $total && (time() - self::$_progressEtaLastUpdate > 1 && $done > self::$_progressEtaLastDone)) {
            $rate = (time() - (self::$_progressEtaLastUpdate ?: self::$_progressStart)) / ($done - self::$_progressEtaLastDone);
            self::$_progressEta = $rate * ($total - $done);
            self::$_progressEtaLastUpdate = time();
            self::$_progressEtaLastDone = $done;
            self::$_progressEtaLastRate = $rate;
        }
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public static function asDuration($value, $implodeString = ', ', $negativeSign = '-')
    {
        if ($value === null) {
            return 'n/а';
        }

        if ($value instanceof DateInterval) {
            $isNegative = $value->invert;
            $interval = $value;
        } elseif (is_numeric($value)) {
            $isNegative = $value < 0;
            $zeroDateTime = (new DateTime())->setTimestamp(0);
            $valueDateTime = (new DateTime())->setTimestamp(abs($value));
            $interval = $valueDateTime->diff($zeroDateTime);
        } elseif (strpos($value, 'P-') === 0) {
            $interval = new DateInterval('P' . substr($value, 2));
            $isNegative = true;
        } else {
            $interval = new DateInterval($value);
            $isNegative = $interval->invert;
        }

        $parts = [];
        if ($interval->y > 0) {
            $parts[] = Yii::t('yii', '{delta, plural, =1{1 year} other{# years}}', ['delta' => $interval->y], 'en');
        }
        if ($interval->m > 0) {
            $parts[] = Yii::t('yii', '{delta, plural, =1{1 month} other{# months}}', ['delta' => $interval->m], 'en');
        }
        if ($interval->d > 0) {
            $parts[] = Yii::t('yii', '{delta, plural, =1{1 day} other{# days}}', ['delta' => $interval->d], 'en');
        }
        if ($interval->h > 0) {
            $parts[] = Yii::t('yii', '{delta, plural, =1{1 hour} other{# hours}}', ['delta' => $interval->h], 'en');
        }
        if ($interval->i > 0) {
            $parts[] = Yii::t('yii', '{delta, plural, =1{1 minute} other{# minutes}}', ['delta' => $interval->i], 'en');
        }
        if ($interval->s > 0) {
            $parts[] = Yii::t('yii', '{delta, plural, =1{1 second} other{# seconds}}', ['delta' => $interval->s], 'en');
        }
        if ($interval->s === 0 && empty($parts)) {
            $parts[] = Yii::t('yii', '{delta, plural, =1{1 second} other{# seconds}}', ['delta' => $interval->s], 'en');
            $isNegative = false;
        }

        return empty($parts) ? 'n/а' : (($isNegative ? $negativeSign : '') . implode($implodeString, $parts));
    }

    /**
     * @inheritdoc
     */
    private static function getRate()
    {
        $rate = 'n/a';

        if (self::$_progressEtaLastRate) {
            $rate = Yii::$app->formatter->asDecimal(1 / self::$_progressEtaLastRate);
        }

        return $rate . ' pcs/s';
    }
}
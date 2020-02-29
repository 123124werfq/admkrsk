<?php
/**
 * С поправкой на красноярский часовой пояс (+7), если локальное время в UTC
 *
 * @var \omnilight\scheduling\Schedule $schedule
 */

// * * * * * php /path/to/yii schedule/run >> /dev/null 2>&1

use backend\models\forms\FiasUpdateSettingForm;
use backend\models\forms\InstitutionUpdateSettingForm;

$fiasSetting = new FiasUpdateSettingForm();
$institutionSetting = new InstitutionUpdateSettingForm();

$schedule->exec('php yii opendata')->dailyAt('02:00'); // 9:00 крск

$schedule->exec('php yii import/institution')->cron($institutionSetting->getExpression());

$schedule->exec('php yii statistic')->everyNMinutes(15); // каждые 15 минут

$schedule->exec('php yii fias/update-location')->hourly(); // каждый час (по 1000 штук)

$schedule->exec('php yii fias/update')->cron($fiasSetting->getExpression());

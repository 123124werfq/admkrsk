<?php
/**
 * С поправкой на красноярский часовой пояс (+7), если локальное время в UTC
 *
 * @var \omnilight\scheduling\Schedule $schedule
 */

// * * * * * php /path/to/yii schedule/run >> /dev/null 2>&1

$schedule->exec('php yii opendata')->dailyAt('02:00'); // 9:00 крск
$schedule->exec('php yii import/institution')->dailyAt('02:00'); // 9:00 крск

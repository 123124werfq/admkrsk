<?php

use common\models\News;
use yii\web\JqueryAsset;
use yii\web\View;

/**
 * @var View $this
 */

$statistics = News::getSubscriberStatistics();
$this->registerJsFile('/js/statistics/jquery.canvasjs.min.js', ['depends' => [JqueryAsset::class]]);
?>

<script>
    window.onload = function () {

        let options = {
            animationEnabled: true,
            title: {
                text: 'Количество подписчиков (по разделам новостей)'
            },
            axisY: {
                title: 'Подписчики',
                suffix: '',
                includeZero: false
            },
            axisX: {
                title: 'Разделы новостей'
            },
            data: [{
                type: 'column',
                yValueFormatString: "####",
                dataPoints: [
                    <?php foreach($statistics as $statistic): ?>
                    {label: '<?= $statistic['title'] ?>', y: <?= $statistic['count'] ?> },
                    <?php endforeach; ?>
                ]
            }]
        };
        $('#chartContainer').CanvasJSChart(options);
    }
</script>
<div id='chartContainer' style='height: 370px; width: 100%;'></div>

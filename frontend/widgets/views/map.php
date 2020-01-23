<?php
use yii\helpers\Html;

$this->registerJsFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=987cf952-38fd-46ee-b595-02977f1247ac',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);
$uniq_id = substr(md5(time().rand(0,9999)),0,10);
?>
<div id="map<?=$uniq_id?>" style="width: <?=$options['width']?>; height: <?=$options['height']?>"></div>

<?php

$jsonPoints = json_encode($points);

$script = <<< JS
    var map$uniq_id;
    
    function updatePoints(mapObject, pointsArray)
    {
        mapObject.geoObjects.removeAll();
        
        for (let counter = 0; counter < pointsArray.length; counter++)
        {
            let pointTemp  = new ymaps.Placemark([pointsArray[counter]['x'], pointsArray[counter]['y']], {
                    balloonContentBody: pointsArray[counter]['content'],
                }, {
                    preset: pointsArray[counter]['icon']
                });
            mapObject.geoObjects.add(pointTemp);
        }
    }
    
    ymaps.ready(init);
    
    function init () {
        map$uniq_id = new ymaps.Map('map$uniq_id', {
            center: [{$options['center_x']}, {$options['center_y']}], 
            zoom: {$options['zoom']},
            controls: ['smallMapDefaultSet']
        }, {
            searchControlProvider: 'yandex#search'
        });
        
        updatePoints(map$uniq_id, $jsonPoints)
        
    }
JS;

$this->registerJs($script, yii\web\View::POS_END);

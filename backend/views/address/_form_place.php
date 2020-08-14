<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Place */
/* @var $form yii\widgets\ActiveForm */

$this->registerJsFile(
    'https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=987cf952-38fd-46ee-b595-02977f1247ac',
    ['position' => View::POS_READY]
);
$this->registerJs("
    ymaps.ready(mapInit);
    
    function mapInit () {
        var myMap, myPlacemark;
    
        function createPlacemark(coords) {
            return new ymaps.Placemark(coords, {
            }, {
                preset: 'islands#redDotIcon',
                draggable: true
            });
        }
    
        function updateCoords(coords)
        {
            $('#place-lat').val(coords[0]);
            $('#place-lon').val(coords[1]);
        }
    
        myMap = new ymaps.Map('map', {
            center: [" . ($model->lat ?: '56.010563') . "," . ($model->lon ?: '92.852572') . "],
            zoom: 12
        }, {
            searchControlProvider: 'yandex#search'
        });

        myMap.events.add('click', function (e) {
            var coords = e.get('coords');

            if (myPlacemark) {
                myPlacemark.geometry.setCoordinates(coords);
            }
            else {
                myPlacemark = createPlacemark(coords);
                myMap.geoObjects.add(myPlacemark);
            }

            updateCoords(myPlacemark.geometry.getCoordinates());
        });
        
        " . (
            !empty($model->lat) && !empty($model->lon) ?
                "myPlacemark = createPlacemark([" . $model->lat . "," . $model->lon . "]);
                                    myMap.geoObjects.add(myPlacemark);" :
                ''
            ) . "
    }
", View::POS_END);

$this->registerJs("$('#lat{$model->id_place}').on('change', function() {
    console.log($(this).val());
})");
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'lat')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'lon')->textInput(['maxlength' => true]) ?>

<div class="form-group">
    <div class="widget-input-map" id="map"></div>
</div>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

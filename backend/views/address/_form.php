<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\House */
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
            $('#house-lat').val(coords[0]);
            $('#house-lon').val(coords[1]);
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

$this->registerJs("$('#lat{$model->id_house}').on('change', function() {
    console.log($(this).val());
})");
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'postalcode')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'id_country')->widget(Select2::class, [
    'data' => $model->id_country ? ArrayHelper::map([$model->country], 'id_country', 'name') : [],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 1,
        'placeholder' => 'Начните ввод',
        'ajax' => [
            'url' => Url::toRoute(['/address/country']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {search:params.term}; }')
        ],
    ],
]) ?>

<?= $form->field($model, 'id_region')->widget(Select2::class, [
    'data' => $model->id_region ? ArrayHelper::map([$model->region], 'id_region', 'name') : [],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 1,
        'placeholder' => 'Начните ввод',
        'ajax' => [
            'url' => Url::toRoute(['/address/region']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {
                    search:params.term,
                    id_country: $(\'#house-id_country\').val()
                }; }')
        ],
    ],
]) ?>

<?= $form->field($model, 'id_subregion')->widget(Select2::class, [
    'data' => $model->id_subregion ? ArrayHelper::map([$model->subregion], 'id_subregion', 'name') : [],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 1,
        'placeholder' => 'Начните ввод',
        'ajax' => [
            'url' => Url::toRoute(['/address/subregion']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {
                    search:params.term,
                    id_region: $(\'#house-id_region\').val()
                }; }')
        ],
    ],
]) ?>

<?= $form->field($model, 'id_city')->widget(Select2::class, [
    'data' => $model->id_city ? ArrayHelper::map([$model->city], 'id_city', 'name') : [],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 1,
        'placeholder' => 'Начните ввод',
        'ajax' => [
            'url' => Url::toRoute(['/address/city']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {
                    search:params.term,
                    id_region: $(\'#house-id_region\').val(),
                    id_subregion: $(\'#house-id_subregion\').val()
                }; }')
        ],
    ],
]) ?>
<?= $form->field($model, 'id_district')->widget(Select2::class, [
    'data' => $model->id_district ? ArrayHelper::map([$model->district], 'id_district', 'name') : [],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 1,
        'placeholder' => 'Начните ввод',
        'ajax' => [
            'url' => Url::toRoute(['/address/district']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {
                    search:params.term,
                    id_region: $(\'#house-id_region\').val()
                }; }')
        ],
    ],
]) ?>
<?= $form->field($model, 'id_street')->widget(Select2::class, [
    'data' => $model->id_street ? ArrayHelper::map([$model->street], 'id_street', 'name') : [],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 1,
        'placeholder' => 'Начните ввод',
        'ajax' => [
            'url' => Url::toRoute(['/address/street']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {
                    search:params.term,
                    id_city: $(\'#house-id_city\').val(),
                    id_district: $(\'#house-id_district\').val()
                }; }')
        ],
    ],
]) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'lat')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'lon')->textInput(['maxlength' => true]) ?>

<div class="form-group">
    <div class="widget-input-map" id="map"></div>
</div>

<?= $form->field($model, 'is_active')->checkbox() ?>

<?= $form->field($model, 'is_updatable')->checkbox() ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

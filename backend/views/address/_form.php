<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\House */
/* @var $form yii\widgets\ActiveForm */
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

<?= $form->field($model, 'is_updatable')->checkbox() ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

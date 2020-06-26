<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\City */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

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
                    id_country: $(\'#house-id_country\').val(),
                    is_active: 0
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
                    is_active: 0
                }; }')
        ],
    ],
]) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'is_active')->checkbox() ?>

<?= $form->field($model, 'is_updatable')->checkbox() ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

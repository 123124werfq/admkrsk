<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\District */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<div class="form-group">
    <?= Html::label('Район') ?>
    <?= Select2::widget([
        'id' => 'id_subregion',
        'name' => 'subregion',
        'data' => $model->city && $model->city->id_subregion ? ArrayHelper::map([$model->city->subregion], 'id_subregion', 'name') : [],
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
</div>

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
                    id_subregion: $(\'#id_subregion\').val(),
                    is_active: 0
                }; }')
        ],
    ],
]) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;

$form = ActiveForm::begin(); 
?>

<?=Select2::widget([
    'data' => [],
    'name'=>'id_form',
    'pluginOptions' => [
        'allowClear' => true,
        'multiple' => false,
        'ajax' => [
            'url' => '/form/list',
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
        'placeholder' => 'Выберите форму',
    ],
    'options' => [
        'multiple' => false,
        'id'=>'form-redactor-id-form',
    ]
]);
?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use common\models\Faq;
use common\models\FaqCategory;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Faq */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?=Select2::widget([
    'data' => [],
    'name'=>'id_faq_category',
    'pluginOptions' => [
        'allowClear' => true,
        'multiple' => false,
        'ajax' => [
            'url' => '/faq-category/list',
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
        'placeholder' => 'Выберите категорию',
    ],
    //'value'=>array_keys($records),
    'options' => [
        'multiple' => false
    ]
]);
?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
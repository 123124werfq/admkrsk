<?php

use common\models\Faq;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Faq */
/* @var $form yii\widgets\ActiveForm */

$model->id_faq_categories = ArrayHelper::getColumn($model->categories, 'id_faq_category')
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'id_faq_categories')->widget(Select2::class, [
    'data' => $model->categories ? ArrayHelper::map($model->categories, 'id_faq_category', 'title') : [],
    'pluginOptions' => [
        'multiple' => true,
        'allowClear' => true,
        'minimumInputLength' => 1,
        'placeholder' => 'Начните ввод',
        'ajax' => [
            'url' => Url::toRoute(['/faq-category/list']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
    ],
]) ?>

<?= $form->field($model, 'status')->dropDownList(Faq::getStatusNames()) ?>

<?= $form->field($model, 'question')->textarea(['rows' => 6, 'class' => 'redactor']) ?>

<?= $form->field($model, 'answer')->textarea(['rows' => 6, 'class' => 'redactor']) ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

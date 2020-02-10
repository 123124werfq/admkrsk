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

$model->id_faq_categories = ArrayHelper::getColumn($model->categories, 'id_faq_category')
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'id_faq_categories')->widget(Select2::class, [
    'data' => FaqCategory::getTree(),
    'pluginOptions' => [
        'multiple' => true,
        'allowClear' => true,
        'placeholder' => 'Выберите категорию',
    ],
]) ?>

<?= $form->field($model, 'status')->dropDownList(Faq::getStatusNames()) ?>

<?= $form->field($model, 'question')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'answer')->textarea(['rows' => 6, 'class' => 'redactor']) ?>

<?php if (Yii::$app->user->can('admin.faq')): ?>

    <hr>

    <h3>Доступ</h3>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

    <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

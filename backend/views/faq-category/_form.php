<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use common\models\FaqCategory;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FaqCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'id_parent')->widget(Select2::class, [
    'data' => FaqCategory::getTree($model->id_faq_category),
    'pluginOptions' => [
        'allowClear' => true,
        'placeholder' => 'Выберите категорию',
    ],
]) ?>

<?= $form->field($model, 'title')->textInput() ?>

<?php if (Yii::$app->user->can('admin.faqCategory')): ?>

    <hr>

    <h3>Доступ</h3>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

    <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

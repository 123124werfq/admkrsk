<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use common\models\Poll;
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Poll */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'status')->dropDownList(Poll::getStatusNames()) ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6, 'class'=>'redactor']) ?>

<?= $form->field($model, 'result')->textarea(['rows' => 6, 'class'=>'redactor']) ?>

<?= $form->field($model, 'is_anonymous')->checkbox() ?>

<?= $form->field($model, 'is_hidden')->checkbox() ?>

<?= $form->field($model, 'date_start')->widget(DateTimePicker::class, [
    'type' => DateTimePicker::TYPE_INPUT,
    'convertFormat' => true,
    'options' => [
        'value' => $model->date_start ? Yii::$app->formatter->asDatetime($model->date_start) : Yii::$app->formatter->asDatetime('+1 day 6:00'),
        'autocomplete' => 'off',
    ],
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'dd.MM.yyyy HH:mm',
    ]
]) ?>

<?= $form->field($model, 'date_end')->widget(DateTimePicker::class, [
    'type' => DateTimePicker::TYPE_INPUT,
    'convertFormat' => true,
    'options' => [
        'value' => $model->date_end ? Yii::$app->formatter->asDatetime($model->date_end) : Yii::$app->formatter->asDatetime('+1 month +1 day 23:59'),
        'autocomplete' => 'off',
    ],
    'pluginOptions' => [
        'autoclose' => true,
        'format' => 'dd.MM.yyyy HH:mm',
    ]
]) ?>

<?php if (Yii::$app->user->can('admin.poll')): ?>

    <hr>

    <h3>Доступ</h3>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

    <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

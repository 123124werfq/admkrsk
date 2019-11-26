<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Page;
/* @var $this yii\web\View */
/* @var $model common\models\ControllerPage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">

    <?php $form = ActiveForm::begin(); ?>

     <?=$form->field($model, 'id_page')->widget(Select2::class, [
        'data' => ArrayHelper::map(Page::find()->all(), 'id_page', 'title'),
        'pluginOptions' => [
            'allowClear' => true,
            'placeholder' => 'Выберите раздел',
        ],
    ])?>

    <?= $form->field($model, 'controller')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'actions')->textarea(['rows' => 6])->hint('вводить через запятую') ?>

    <?php if (Yii::$app->user->can('admin.controllerPage')): ?>

        <hr>

        <h3>Доступ</h3>

        <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

        <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

    <?php endif; ?>

    <hr>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success'])?>

    <?php ActiveForm::end(); ?>
    </div>
</div>

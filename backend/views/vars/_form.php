<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Vars */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="container">
<div class="ibox m-t">
    <div class="ibox-content">
        <?php $form = ActiveForm::begin(); ?>

	    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

	    <?= $form->field($model, 'alias')->textInput(['maxlength' => 255]) ?>

	    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

        <?php if (Yii::$app->user->can('admin.vars')): ?>

            <hr>

            <h3>Доступ</h3>

            <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

            <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

        <?php endif; ?>

	    <div class="hr-line-dashed"></div>
        <div class="text-right">
            <?=Html::a('Отмена', ['index'], ['class' => 'btn btn-default'])?>
            <?=Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
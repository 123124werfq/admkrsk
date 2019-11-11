<?php

use backend\widgets\UserAccessControl;
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

            <?= $form->field($model, 'access_user_ids')->widget(UserAccessControl::class) ?>

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
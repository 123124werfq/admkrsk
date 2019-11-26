<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Gallery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">
        <?php $form = ActiveForm::begin(); ?>
        <?='' //$form->field($model, 'id_page')->textInput() ?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

         <?=\common\components\multifile\MultiFileWidget::widget([
            'model'=>$model,
            'single'=>false,
            'relation'=>'medias',
            'extensions'=>['jpg','jpeg','gif','png'],
            'grouptype'=>1,
            'showPreview'=>true
        ]);?>

        <?php if (Yii::$app->user->can('admin.gallery')): ?>

            <hr>

            <h3>Доступ</h3>

            <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

            <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

        <?php endif; ?>

        <hr>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php

use backend\widgets\UserAccessControl;
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

            <?= $form->field($model, 'access_user_ids')->widget(UserAccessControl::class) ?>

        <?php endif; ?>

        <hr>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

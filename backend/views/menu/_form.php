<?php

use backend\widgets\UserAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">
        <?php $form = ActiveForm::begin(); ?>
            <?php if (empty($model->id_page)){?>
            <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'type')->dropDownList($model->types) ?>
            <?php }?>
            <?= $form->field($model, 'state')->dropDownList([0=>'Не активно',1=>'Активно']) ?>

            <?php if ($model->isNewRecord || $model->type==$model::TYPE_LIST){
                echo $this->render('_links',['model'=>$model]);
            }?>

            <?php if (Yii::$app->user->can('admin.menu') && empty($model->id_page)): ?>
                <?= $form->field($model, 'access_user_ids')->widget(UserAccessControl::class) ?>
            <?php endif; ?>
            <hr/>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

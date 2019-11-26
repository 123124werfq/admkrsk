<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
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

                <hr>

                <h3>Доступ</h3>

                <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

                <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

            <?php endif; ?>
            <hr/>
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\ServiceRubric;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceRubric */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-sm-9">
        <div class="ibox">
            <div class="ibox-content">
                <?php $form = ActiveForm::begin(); ?>

                <?=$form->field($model, 'id_parent')->widget(Select2::class, [
                    'data' => ArrayHelper::map(ServiceRubric::find()->where('id_parent IS NULL AND id_rub <> '.(int)$model->id_rub)->all(), 'id_rub', 'name'),
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => 'Выберите родительскую рубрику',
                    ],
                ])?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                <?php if (Yii::$app->user->can('admin.serviceRubric')): ?>

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
    </div>
</div>
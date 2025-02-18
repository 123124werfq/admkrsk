<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Form;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceTarget */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'state')->checkBox() ?>

        <?=$form->field($model, 'id_form')->widget(Select2::class, [
                'data' => ArrayHelper::map(Form::find()->where(['state'=>1, 'is_template'=>0])->all(), 'id_form', 'name'),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => 'Форма приема заявления',
                ],
            ])?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'place')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'reestr_number')->textInput(['maxlength' => 255]) ?>

        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'target')->textInput(['maxlength' => 255]) ?>
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'target_code')->textInput(['maxlength' => 255]) ?>
            </div>
        </div>

        <?= $form->field($model, 'service_code')->textInput(['maxlength' => 255]) ?>

        <?= $form->field($model, 'obj_name')->textInput(['maxlength' => 255]) ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
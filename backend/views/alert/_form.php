<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model common\models\Alert */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-sm-9">
        <div class="ibox">
            <div class="ibox-content">

                <?php $form = ActiveForm::begin(); ?>

                <?=$form->field($model, 'id_page')->widget(Select2::class, [
                    'data' => ArrayHelper::map(\common\models\Page::find()->all(), 'id_page', 'title'),
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => 'Выберите раздел',
                    ],
                ])?>

                <?= $form->field($model, 'content')->textInput(['class' => 'redactor']) ?>

                 <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'date_begin')->textInput(['type'=>'datetime-local','value'=>(!empty($model->date_begin))?date('Y-m-d\TH:i:s', $model->date_begin):'']) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'date_end')->textInput(['type'=>'datetime-local','value'=>(!empty($model->date_end))?date('Y-m-d\TH:i:s',$model->date_end):'']) ?>
                    </div>
                </div>

                <?= $form->field($model, 'state')->checkBox() ?>

                <hr>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
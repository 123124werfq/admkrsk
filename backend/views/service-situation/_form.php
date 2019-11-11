<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\ServiceSituation;

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
                    'data' => ArrayHelper::map(ServiceSituation::find()->where('id_parent IS NULL AND id_situation <> '.(int)$model->id_situation)->all(), 'id_situation', 'name'),
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => 'Выберите родительскую рубрику',
                    ],
                ])?>

                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                   <?=common\components\multifile\MultiFileWidget::widget([
                    'model'=>$model,
                    'single'=>true,
                    'relation'=>'media',
                    'extensions'=>['jpg','jpeg','gif','png','svg'],
                    'grouptype'=>1,
                    'showPreview'=>true
                ]);?>

                <hr>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
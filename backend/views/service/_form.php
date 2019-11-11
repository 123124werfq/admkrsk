<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\ServiceRubric;
use common\models\Form;
use common\models\ServiceSituation;
/* @var $this yii\web\View */
/* @var $model common\models\Service */
/* @var $form yii\widgets\ActiveForm */

$id_situations = $model->getSituations()->indexBy('id_situation')->all();

if (!empty($id_situations))
    $model->id_situations = array_keys($id_situations);
?>

<div class="ibox">
    <div class="ibox-content">

        <?php $form = ActiveForm::begin(); ?>

        <?=$form->field($model, 'id_rub')->widget(Select2::class, [
            'data' => ArrayHelper::map(ServiceRubric::find()->joinWith('childs as childs')->all(), 'id_rub', 'name'),
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => 'Выберите рубрику',
            ],
        ])?>

        <?=$form->field($model, 'id_situations')->widget(Select2::class, [
            'data' => ArrayHelper::map(ServiceSituation::find()->all(), 'id_situation', 'name'),
            'pluginOptions' => [
                'allowClear' => true,
                'tags'=>true,
                'placeholder' => 'Выберите ситуацию',
            ],
            'options'=>[
                'multiple'=>true,
            ]
        ])?>

        <?= $form->field($model, 'client_type')->checkBoxList([2=>'Физ. лица', 4=>'Юр. лица'])?>

        <?= $form->field($model, 'reestr_number')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'keywords')->textarea(['rows' => 6]) ?>

        <hr/>

        <?= $form->field($model, 'addresses')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'result')->textarea(['rows' => 6,'class'=>'form-controll redactor']) ?>

        <?= $form->field($model, 'client_category')->textarea(['rows' => 6,'class'=>'form-controll redactor']) ?>

        <?= $form->field($model, 'duration')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'documents')->textarea(['rows' => 6,'class'=>'form-controll redactor']) ?>

        <?= $form->field($model, 'price')->textarea(['rows' => 6,'class'=>'form-controll redactor']) ?>

        <?= $form->field($model, 'appeal')->textarea(['rows' => 6,'class'=>'form-controll redactor']) ?>

        <?= $form->field($model, 'legal_grounds')->textarea(['rows' => 6,'class'=>'form-controll redactor']) ?>

        <?= $form->field($model, 'regulations')->textarea(['rows' => 6,'class'=>'form-controll redactor']) ?>

        <?= $form->field($model, 'regulations_link')->textarea(['rows' => 6,'class'=>'form-controll redactor']) ?>

        <?= $form->field($model, 'duration_order')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'availability')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'procedure_information')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'max_duration_queue')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'old')->checkBox() ?>

        <?= $form->field($model, 'online')->dropDownList([0=>'Оффлайн',1=>'Онлайн']) ?>

        <?=$form->field($model, 'id_form')->widget(Select2::class, [
            'data' => ArrayHelper::map(Form::find()->all(), 'id_form', 'name'),
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => 'Форма приема заявления',
            ],
        ])?>

        <hr>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>

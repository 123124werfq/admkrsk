<?php

use common\models\Institution;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Institution */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'status')->dropDownList(Institution::getStatusNames()) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'comment')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'bus_id')->textInput(['disabled' => true]) ?>

<?= $form->field($model, 'version')->textInput(['disabled' => true]) ?>

<?= $form->field($model, 'modified_at')->textInput(['disabled' => true, 'value' => Yii::$app->formatter->asDatetime($model->modified_at)]) ?>

<?= $form->field($model, 'last_update')->textInput(['disabled' => true, 'value' => Yii::$app->formatter->asDatetime($model->last_update)]) ?>

<?= $form->field($model, 'is_updating')->checkbox() ?>

<?= $form->field($model, 'type')->dropDownList(Institution::getTypeNames()) ?>

<?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'shortname')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'okved_code')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'okved_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'ppo')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'ppo_oktmo_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'ppo_oktmo_code')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'ppo_okato_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'ppo_okato_code')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'okpo')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'okopf_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'okopf_code')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'okfs_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'okfs_code')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'oktmo_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'oktmo_code')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'okato_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'okato_code')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'address_zip')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'address_subject')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'address_region')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'address_locality')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'address_street')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'address_building')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'address_latitude')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'address_longitude')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'vgu_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'vgu_code')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'inn')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'kpp')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'ogrn')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'manager_position')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'manager_firstname')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'manager_middlename')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'manager_lastname')->textInput(['maxlength' => true]) ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

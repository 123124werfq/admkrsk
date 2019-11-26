<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CollectionColumn;
use common\models\EsiaUser;
use common\models\Service;
use common\models\Collection;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\FormInputType */
/* @var $form yii\widgets\ActiveForm */

$esia = new EsiaUser;
$esia = $esia->attributeLabels();

$service = new Service;
$service = $service->attributeLabels();

unset($esia['id_esia_user']);
unset($esia['id_user']);
unset($esia['is_org']);
unset($esia['created_at']);
unset($esia['created_by']);
unset($esia['updated_at']);
unset($esia['deleted_at']);
unset($esia['deleted_by']);
?>

<div class="ibox">
    <div class="ibox-content">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type')->dropDownList(CollectionColumn::getTypeLabel()) ?>

        <?= $form->field($model, 'values')->textarea(['rows' => 3])->hint('Ввести возможные значения через запятую') ?>

        <?= $form->field($model, 'regexp')->textInput(['visible' => 'forminputtype-type','visible-value'=>1]) ?>

        <div class="row">
            <div class="col-sm-4">
                <?= $form->field($model, 'esia')->dropDownList($esia,['prompt'=>'Выберите поле из ЕСИА','visible' => 'forminputtype-type','visible-value'=>3])->hint('Данные в форме будут заполнятся автоматически')?>
            </div>
            <div class="col-sm-4">
                <?=$form->field($model, 'id_collection')->widget(Select2::class, [
                    'data' => ArrayHelper::map(Collection::find(),'id_collection','name'),
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => 'Выберите коллекцию',
                    ],
                    'options'=>[
                        'visible' => 'forminputtype-type',
                        'visible-value'=>3,
                    ]
                ])?>
            </div>
            <div class="col-sm-4">
                <?= $form->field($model, 'service_attribute')->dropDownList($service,['prompt'=>'Выберите поле из услуги','visible' => 'forminputtype-type','visible-value'=>3])->hint('Данные в форме будут сформированы исходя из возможных данных')?>
            </div>
        </div>

        <?php if (Yii::$app->user->can('admin.formInputType')): ?>

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

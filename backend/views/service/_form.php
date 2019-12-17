<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\ServiceRubric;
use common\models\Form;
use common\models\Collection;
use common\models\ServiceSituation;

/* @var $this yii\web\View */
/* @var $model common\models\Service */
/* @var $form yii\widgets\ActiveForm */

$id_situations = $model->getSituations()->indexBy('id_situation')->all();

if (!empty($id_situations))
    $model->id_situations = array_keys($id_situations);

$id_firms = $model->getFirms()->indexBy('id_record')->all();

if (!empty($id_firms))
    $model->id_firms = array_keys($id_firms);

$offices = Collection::find()->where(['alias'=>'service_offices'])->one();

if (!empty($offices))
    $offices = $offices->getArray();

/*var_dump($model->client_type);

$client_types = [];
foreach ($model->client_type as $key => $value)
    $client_types[$value]=$value;

$model->client_type = $client_types;

var_dump($model->client_type);*/

?>
<div class="ibox">
    <div class="ibox-content">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'old')->checkBox() ?>

        <?= $form->field($model, 'show_forms')->checkBox() ?>

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

        <hr>

        <?= $form->field($model, 'ext_url')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'reestr_number')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'online')->dropDownList([0=>'Оффлайн',1=>'В электронном виде']) ?>

        <?= $form->field($model, 'client_type')->checkBoxList($model::getAttributeValues('client_type'))?>

        <?= $form->field($model, 'type')->dropDownList($model::getAttributeValues('type'),['prompt'=>'Выберите значение'])?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => 500]) ?>

        <?= $form->field($model, 'fullname')->textArea() ?>

        <?= $form->field($model, 'keywords')->textarea(['rows' => 6]) ?>

        <hr/>

        <?=$form->field($model, 'id_firms')->widget(Select2::class, [
            'data' => $offices,
            'pluginOptions' => [
                'allowClear' => true,
                'tags'=>true,
                'placeholder' => 'Выберите офисы',
            ],
            'options'=>[
                'multiple'=>true,
            ]
        ])?>

        <?= $form->field($model, 'addresses')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'result')->textarea(['rows' => 6,'class'=>'form-controll redactor']) ?>

        <?= $form->field($model, 'client_category')->textarea(['rows' => 6,'class'=>'form-controll redactor']) ?>

        <?= $form->field($model, 'duration')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'refuse')->textarea(['rows' => 6,'class'=>'form-controll redactor']) ?>

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

        <h3>Шаблон документа</h3>

        <?=common\components\multifile\MultiFileWidget::widget([
            'model'=>$model,
            'single'=>true,
            'relation'=>'template',
            'extensions'=>['docx'],
            'grouptype'=>1,
            'showPreview'=>false
        ]);?>

        <?php if (Yii::$app->user->can('admin.service')): ?>
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

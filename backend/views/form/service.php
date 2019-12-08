<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model common\models\Form */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Добавить форму услуги';
$this->params['breadcrumbs'][] = ['label' => 'Услуги', 'url' => ['service/index']];
$this->params['breadcrumbs'][] = ['label' => 'Формы', 'url' => ['index']];
?>

<div class="ibox">
    <div class="ibox-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'state')->checkBox()?>

    <?= $form->field($model, "id_service")->widget(Select2::class, [
            'data' => ArrayHelper::map(\common\models\Service::find()->all(), 'id_service', 'reestr_number'),
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => 'Выберите услугу',
            ],
        ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

    <h3>Шаблон документа</h3>
    <?=common\components\multifile\MultiFileWidget::widget([
        'model'=>$model,
        'single'=>true,
        'relation'=>'template',
        'extensions'=>['docx'],
        'grouptype'=>1,
        'showPreview'=>false
    ]);?>

    <p class="help-text">заполняется если нужен отдельный шаблон</p>

    <hr>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
    </div>
</div>
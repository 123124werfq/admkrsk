<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\models\Box;

/* @var $this yii\web\View */
/* @var $model common\models\Form */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-4"><?= $form->field($model, 'is_template')->checkBox()?></div>
        <div class="col-sm-4"><?= $form->field($model, 'state')->checkBox()?></div>
    </div>

    <?= $form->field($model, 'id_box')->dropDownList(ArrayHelper::map(Box::find()->all(), 'id_box', 'name'),['prompt'=>'Выберите группу']) ?>

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

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'id_page')->widget(Select2::class, [
                'data' => $model->id_page ? [$model->id_page=>$model->page->title]:[],
                'pluginOptions' => [
                    'multiple' => false,
                    'allowClear' => true,
                    'minimumInputLength' => 2,
                    'placeholder' => 'Начните ввод',
                    'ajax' => [
                        'url' => '/page/list',
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($model, 'message_success')->textArea(['maxlength' => true,'class'=>'redactor']) ?>

    <?= $form->field($model, 'maxfilesize')->textInput(['type' => 'number'])->hint('вввод в мегабайтах') ?>

    <?= $form->field($model, 'captcha')->dropDownList([
            $model::CAPTCHA_NO=>'Без каптчи',
            $model::CAPTCHA_YES=>'Требовать ввод каптчи',
            $model::CAPTCHA_AUTH=>'Требовать ввод каптчи для неавторизованных',
    ])?>

    <h3>Шаблон документа</h3>
    <?=common\components\multifile\MultiFileWidget::widget([
        'model'=>$model,
        'single'=>true,
        'relation'=>'template',
        'extensions'=>['docx'],
        'grouptype'=>1,
        'showPreview'=>false
    ]);?>

    <?php if (Yii::$app->user->can('admin.form')): ?>
        <hr>
        <h3>Доступ</h3>
        <?php
            $records = $model->getRecords('partitions');
            if (!empty($records[0]->id_page))
                $records = ArrayHelper::map($records, 'id_page', 'title');
            else
                $records = [];

            echo Select2::widget([
                'data' => $records,
                'name'=>'Page[partitions][]id_page',
                'pluginOptions' => [
                    'allowClear' => true,
                    'multiple' => true,
                    'ajax' => [
                        'url' => '/page/list',
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term, partition:1}; }')
                    ],
                    'placeholder' => 'Выберите разделы',
                ],
                'options' => [
                    'multiple' => true,
                    'value'=>array_keys($records),
                ]
            ]);
        ?>
        <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>
        <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

    <?php endif; ?>

    <hr>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
    </div>
</div>

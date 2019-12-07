<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model common\models\Form */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

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
        <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>
        <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>
    <?php endif; ?>

    <hr>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
    </div>
</div>

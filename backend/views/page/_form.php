<?php

use backend\widgets\UserAccessControl;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Page;

/* @var $this yii\web\View */
/* @var $model common\models\Page */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

 <?=$form->field($model, 'id_parent')->widget(Select2::class, [
    'data' => ArrayHelper::map(Page::find()->where('id_page <> '.(int)$model->id_page)->all(), 'id_page', 'title'),
    'pluginOptions' => [
        'allowClear' => true,
        'placeholder' => 'Выберите родительский раздел',
    ],
])?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'alias',['template'=>'
                                        {label}
                                        <div class="input-group">
										'.($model->isNewRecord?'':'
                                        <div class="input-group-prepend">
                                            <span class="input-group-addon"><a class="btn" href="'.$model->getUrl(true).'" target="_blank">Перейти</a></span>
                                        </div>').'
                                        {input}
                                    </div>'])->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'content')->textarea(['rows' => 6, 'class'=>'redactor']) ?>

<?= $form->field($model, 'seo_title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'seo_description')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'seo_keywords')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'active')->checkBox() ?>

<hr>

<?= $form->field($model, 'noguest')->checkBox() ?>

<?php if (Yii::$app->user->can('admin.page')): ?>

<?= $form->field($model, 'access_user_ids')->widget(UserAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

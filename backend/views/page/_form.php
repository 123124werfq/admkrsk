<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
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

<?= $form->field($model, 'id_parent')->widget(Select2::class, [
    'data' => $model->id_parent ? [$model->id_parent=>$model->parent->title]:[],
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

<?= $form->field($model, 'hidemenu')->checkBox() ?>

<hr>

<?= $form->field($model, 'noguest')->checkBox() ?>

<?php if (Yii::$app->user->can('admin.page')): ?>

    <hr>

    <h3>Доступ</h3>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

    <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

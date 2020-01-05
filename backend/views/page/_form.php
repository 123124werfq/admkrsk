<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use common\components\multifile\MultiFileWidget;
use common\models\MailNotifyManager;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

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

<div class="row">
    <div class="col-sm-4"><?= $form->field($model, 'active')->checkBox() ?></div>
    <div class="col-sm-4"><?= $form->field($model, 'hidemenu')->checkBox() ?></div>
    <div class="col-sm-4"><?= $form->field($model, 'noguest')->checkBox() ?></div>
</div>

<h3>Файлы внизу страницы</h3>

<?= MultiFileWidget::widget([
    'model'=>$model,
    'single'=>false,
    'relation'=>'medias',
    'grouptype'=>1,
    'showPreview'=>true
]);?>

<?php if (Yii::$app->user->can('admin.page')): ?>

    <hr>

    <h3>Доступ</h3>

    <?php if (Yii::$app->user->can('admin.collection')): ?>
        <?= $form->field($model, 'is_admin_notify')->checkbox(
            [
                'checked' => MailNotifyManager::isAdminNotify($model->primaryKey, get_class($model)),
                'label' => 'Уведомлять админа об изменении списка?'])
        ?>
    <?php endif; ?>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

    <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

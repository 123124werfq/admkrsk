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

<?php $form = ActiveForm::begin();
?>

<?php if ($model->alias!='/'){?>
<?= $form->field($model, 'id_parent')->widget(Select2::class, [
    'data' => (!empty($model->parent))?[$model->id_parent=>$model->parent->title]:[],
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
    'options'=>[
        'prompt'=>'Выберите родителя'
    ]
]) ?>
<?php }?>

<?= $form->field($model, 'created_at')->textInput(['type'=>'date','value'=>(!empty($model->created_at))?date('Y-m-d', $model->created_at):'']) ?>


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

<?= $form->field($model, 'is_partition')->checkBox()?>

<?= $form->field($model, 'partition_domain')->textInput(['maxlength' => 255])->hint('Заполняется если это раздел. Все страницы данного раздела будут строится относительно этого домена. Вводить без "/" на конце') ?>

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
    <h3>Настройка уведомлений</h3>
    <?= $form->field($model, 'notify_rule')->radioList(
        [
            0 => 'Отключить уведомления',
            1 => 'чем 30 минут',
            2 => 'чем 1 час',
            3 => 'чем 3 час',
        ],
        [
            'separator' => '&nbsp;&nbsp;&nbsp;</br>',
        ])->label('Получать уведомления не чаще') ?>

    <?= $form->field($model, 'notify_message')->textarea()->label('Текст сообщения') ?>

    <?php if (Yii::$app->user->can('admin.collection')): ?>
        <?= $form->field($model, 'is_admin_notify')->checkbox(
            [
                'checked' => MailNotifyManager::isAdminNotify($model->primaryKey, get_class($model)),
                'label' => 'Уведомлять админа об изменении списка?'])
        ?>
    <?php endif; ?>

    <hr>
    <h3>Доступ</h3>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>
    <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>
<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
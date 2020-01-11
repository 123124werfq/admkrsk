<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use common\models\MailNotifyManager;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\CollectionColumn;
use common\models\Collection;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Collection */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'id_group')->dropDownList(Collection::getArrayByAlias('collection_group')) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

<?php if (Yii::$app->user->can('admin.collection')): ?>

    <hr>
    <?= $form->field($model, 'template')->textInput(['class' => 'form-control redactor']) ?>

    <?= $form->field($model, 'template_element')->textInput(['class' => 'form-control redactor']) ?>

    <table class="table">
        <?php foreach ($model->columns as $key => $column) {
            echo '<tr><th width="100">' . ($column->alias ? $column->alias : 'column_' . $column->id_column) . '</th><td>' . $column->name . '</td></tr>';
        } ?>
    </table>

    <?= $form->field($model, 'is_authenticate')->checkbox() ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?php if (!$model->isNewRecord) { ?>
        <?= $form->field($model, 'label')->widget(Select2::class, [
            'data' => ArrayHelper::map(CollectionColumn::find()->where([
                'id_collection' => $model->id_collection,
                'type' => [CollectionColumn::TYPE_INPUT, CollectionColumn::TYPE_INTEGER]
            ])->all(), 'id_column', 'name'),
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => true,
                'placeholder' => 'Выберите колонки',
            ],
            'options' => ['multiple' => true,]
        ])->hint('Выберите колонки из которых будет составляться представление для отображения в списках') ?>
    <?php } ?>

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

    <?php
    $records = $model->getRecords('partitions');
    $records = ArrayHelper::map($records, 'id_page', 'title');

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
        'value'=>array_keys($records),
        'options' => [
            'multiple' => true
        ]
    ]);
    //->hint('Выберите разделы в которых можно использовать данный список');
    ?>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

    <?= $form->field($model,
        'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

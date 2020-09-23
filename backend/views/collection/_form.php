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
use common\models\CollectionType;
use common\models\Box;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Collection */
/* @var $form yii\widgets\ActiveForm */

Yii::$app->params['tinymce_plugins'][] = 'recordmap';
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'id_box')->dropDownList(ArrayHelper::map(Box::find()->all(), 'id_box', 'name'),['prompt'=>'Выберите группу']) ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

<hr>
<?= $form->field($model, 'template')->textInput(['class' => 'form-control redactor']) ?>

<?= $form->field($model, 'template_element')->textInput(['class' => 'form-control redactor']) ?>

<table class="table">
    <?php foreach ($model->columns as $key => $column) {
        echo '<tr><th width="100">' . ($column->alias ? $column->alias : 'column_' . $column->id_column) . '</th><td>' . $column->name . '</td></tr>';
    } ?>
</table>

<?php if (!$model->isNewRecord) { ?>
    <?= $form->field($model, 'label')->widget(Select2::class, [
        'data' => ArrayHelper::map($model->getColumns()->andWhere([
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

    <?= $form->field($model, 'id_column_map')->dropDownList(
        ArrayHelper::map($model->getColumns()->andWhere([
                'id_collection' => $model->id_collection,
        ])->andWhere(['or',['type' => CollectionColumn::TYPE_MAP],['type' => CollectionColumn::TYPE_ADDRESS]])->all(),'id_column','name'),['prompt'=>'Выберите колонку'])->hint('В колонки должны содержаться координаты');
    ?>
<?php } ?>

<?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'is_authenticate')->checkbox() ?>

<?php if (Yii::$app->user->can('admin.collection')): ?>



    <?php if ($model->isNewRecord){?>
    <?= $form->field($model, 'id_type')->dropDownList(ArrayHelper::map(CollectionType::find()->all(), 'id_type', 'name')
        ,['prompt'=>'Выберите тип'])->hint('Заполняется только для создания специальных типов списков') ?>
    <?php }?>

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
            'value'=>array_keys($records),
            'options' => [
                'multiple' => true
            ]
        ]);
    ?>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

    <?= $form->field($model,
        'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
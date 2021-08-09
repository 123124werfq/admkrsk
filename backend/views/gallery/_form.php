<?php

use backend\widgets\GalleryGroupsWidget;
use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use common\components\multifile\MultiFileWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Gallery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">
        <?php $form = ActiveForm::begin(); ?>
        <?='' //$form->field($model, 'id_page')->textInput() ?>
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= MultiFileWidget::widget([
            'model' => $model,
            'single' => false,
            'relation' => 'medias',
            'showAuthor' => true,
            'extensions' => ['jpg', 'jpeg', 'gif', 'png', 'mp4'],
            'grouptype' => 1,
            'showPreview' => true
        ]); ?>

        <?php if (Yii::$app->user->can('admin.gallery')): ?>

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

            <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

        <?php endif; ?>

        <hr>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

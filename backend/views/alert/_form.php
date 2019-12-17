<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model common\models\Alert */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col-sm-9">
        <div class="ibox">
            <div class="ibox-content">

                <?php $form = ActiveForm::begin(); ?>

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

                <?= $form->field($model, 'content')->textInput(['class' => 'redactor']) ?>

                 <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'date_begin')->textInput(['type'=>'datetime-local','value'=>(!empty($model->date_begin))?date('Y-m-d\TH:i', $model->date_begin):'']) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'date_end')->textInput(['type'=>'datetime-local','value'=>(!empty($model->date_end))?date('Y-m-d\TH:i',$model->date_end):'']) ?>
                    </div>
                </div>

                <?= $form->field($model, 'state')->checkBox() ?>

                <?php if (Yii::$app->user->can('admin.alert')): ?>

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
    </div>
</div>
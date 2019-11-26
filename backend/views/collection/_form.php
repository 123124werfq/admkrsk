<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use common\models\CollectionColumn;
use common\models\Collection;
/* @var $this yii\web\View */
/* @var $model common\models\Collection */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

<!--div class="row">
    <div class="col-md-2">
        <label class="control-label">Название</label>
    </div>
    <div class="col-md-2">
        <label class="control-label">Имя переменной</label>
    </div>
    <div class="col-md-2">
        <label class="control-label">Тип</label>
    </div>
</div>
<div id="collection-columns" class="multiyiinput sortable">
    <?php /*
    $records = $model->getRecords('columns');
    foreach ($records as $key => $data) {?>
        <div class="row">
            <div class="col-md-2">
                <?=Html::hiddenInput("CollectionColumn[columns][$key][ord]",$data->ord);?>
                <?=Html::hiddenInput("CollectionColumn[columns][$key][id_column]",$data->id_column);?>
                <div class="form-group">
                    <?=Html::textInput("CollectionColumn[columns][$key][name]",$data->name,['required'=>true,'class'=>'form-control','id'=>'CollectionColumn_name_'.$key,'placeholder'=>'Введите название']);?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <?=Html::textInput("CollectionColumn[columns][$key][alias]",$data->alias,['class'=>'form-control','id'=>'CollectionColumn_alias_'.$key,'placeholder'=>'Имя переменной']);?>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <?=Html::dropDownList("CollectionColumn[columns][$key][type]",$data->type,CollectionColumn::getTypeLabel(),['required'=>true,'class'=>'form-control column-type','id'=>'CollectionColumn_type'.$key]);?>
                </div>
            </div>
            <div class="col-md-1 col-close">
                <a class="close btn" href="#">&times;</a>
            </div>
            <div class="col-md-5 <?=$data->type==CollectionColumn::TYPE_SELECT || $data->type==CollectionColumn::TYPE_COLLECTION?'':'hide'?>">
                <div class="form-group">
                    <?php
                    switch ($data->type) {
                        case CollectionColumn::TYPE_SELECT:
                            echo Html::textInput("CollectionColumn[columns][$key][variables]",$data->variables,['class'=>'form-control','id'=>'CollectionColumn_variables'.$key,'placeholder'=>'Введите возможные значения']);
                            break;
                        case CollectionColumn::TYPE_COLLECTION:
                            echo Html::dropDownList("CollectionColumn[columns][$key][variables]",$data->variables,ArrayHelper::map(Collection::find()->all(), 'id_collection', 'name'),['class'=>'form-control','id'=>'CollectionColumn_variables'.$key,'placeholder'=>'Введите возможные значения']);
                            break;
                    }?>
                </div>
            </div>
        </div>
    <?php }*/?>
</div>
<a onclick="return addInput('collection-columns')" href="#" class="btn btn-default">Добавить еще</a-->

<?php if (Yii::$app->user->can('admin.collection')): ?>
    <hr>

    <?= $form->field($model, 'template')->textInput(['class' => 'form-control redactor'])?>

    <?= $form->field($model, 'template_element')->textInput(['class' => 'form-control redactor'])?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <hr>

    <h3>Доступ</h3>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

    <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

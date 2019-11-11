<?php

use backend\widgets\UserAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use common\models\CollectionColumn;
use common\models\Collection;
/* @var $this yii\web\View */
/* @var $model common\models\Collection */
/* @var $form yii\widgets\ActiveForm */

$columns = ArrayHelper::map($model->parent->columns, 'id_column', 'name');
$filtes = $model->getViewFilters();
$operators = [
    '='=>'=',
    '>'=>'>',
    '>='=>'>=',
    '<'=>'<',
    '<='=>'<=',
    '<>'=>'<>'
];

if (empty($filtes))
    $filtes = [
        [
            'id_column'=>'',
            'operator'=>'',
            'value'=>'',
        ]
    ]
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<h3>Условия</h3>
<div class="row">
    <div class="col-md-2">
        <label class="control-label">Колонка</label>
    </div>
    <div class="col-md-1">
        <label class="control-label">Условие</label>
    </div>
    <div class="col-md-2">
        <label class="control-label">Значение</label>
    </div>
</div>
<div id="view-filters" class="multiyiinput">
    <?php foreach ($filtes as $key => $data) {?>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <?=Html::dropDownList("ViewFilters[$key][id_column]",$data['id_column'],$columns,['required'=>true,'class'=>'form-control','id'=>'CollectionColumn_id_column'.$key]);?>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <?=Html::dropDownList("ViewFilters[$key][operator]",$data['operator'],['='=>'=','>','>=','<','<=','<>'],['required'=>true,'class'=>'form-control','id'=>'CollectionColumn_operator'.$key]);?>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <?=Html::textInput("ViewFilters[$key][value]",$data['value'],['required'=>true,'class'=>'form-control','id'=>'CollectionColumn_value_'.$key,'placeholder'=>'Введите название']);?>
                </div>
            </div>
        </div>
    <?php }?>
</div>
<a onclick="return addInput('view-filters')" href="#" class="btn btn-default">Добавить еще</a>

<h3>Поля</h3>
<div class="row">
    <div class="col-md-2">
        <label class="control-label">Колонка</label>
    </div>
    <div class="col-md-2">
        <label class="control-label">Шаблон значения</label>
    </div>
</div>
<div id="view-columns" class="multiyiinput sortable">
    <?php
        //$records = $model->getRecords('columns');
    ?>
    <?php foreach ($model->getViewColumns() as $key => $data) {?>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <?=Html::dropDownList("ViewColumns[$key][id_column]",$data['id_column'],$columns,['class'=>'form-control','id'=>'CollectionColumn_id_column_'.$key,'placeholder'=>'Выберите колонку']);?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <?=Html::textInput("ViewColumns[$key][value]",$data->alias,['class'=>'form-control','id'=>'value_'.$key,'placeholder'=>'Введите шаблон']);?>
                </div>
            </div>
            <div class="col-md-1 col-close">
                <a class="close btn" href="#">&times;</a>
            </div>
        </div>
    <?php }?>
</div>
<a onclick="return addInput('view-columns')" href="#" class="btn btn-default">Добавить еще</a>

<?php if (Yii::$app->user->can('admin.collection')): ?>
    <hr>
    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'access_user_ids')->widget(UserAccessControl::class) ?>
<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

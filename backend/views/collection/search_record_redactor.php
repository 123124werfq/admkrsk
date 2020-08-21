<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Collection */
/* @var $form yii\widgets\ActiveForm */

$model->filters = $rules = $model->getViewFilters();
$model->filters = json_encode($model->filters);
?>

<?php $form = ActiveForm::begin(['id'=>'collection-redactor']); ?>

<?php if (empty($model->id_collection)){?>
    <?= $form->field($model, 'id_collection')->widget(Select2::class, [
        'data' => [],
        'pluginOptions' => [
            'multiple' => false,
            'allowClear' => true,
            'minimumInputLength' => 1,
            'placeholder' => 'Начните ввод',
            'ajax' => [
                'url' => '/collection/list',
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
        ],
    ])->label('Выберите список')?>
<?php
}
else
{
    $columns = $model->collection->columns;
    $columns_dropdown = [];

    foreach ($columns as $key => $column)
    {
        $columns_dropdown[$column->id_column] = $column->name;
    }

    $columns = $columns_dropdown;


?>

    <?=$form->field($model, 'id_collection',['template'=>'{input}'])->hiddenInput();?>

    <h3>Поля</h3>
    <br/>
    <div class="row">
        <div class="col-sm-5">
            <label class="control-label">Колонка</label>
        </div>
    </div>
    <div id="view-columns" class="multiyiinput sortable">
        <?php foreach ($model->getViewColumnsOrFirstColumn() as $key => $data) {?>
            <?php $showDetail = !empty($data['showdetails'])? true : false; ?>
        <div class="row" data-row="<?=$key?>">
            <div class="col-sm-5">
                <div class="form-group">
                    <?=Html::dropDownList("ViewColumns[$key][id_column]",$data['id_column'],$columns,['class'=>'form-control','id'=>'CollectionColumn_id_column_'.$key]);?>
                </div>
            </div>
            <div class="col-sm-1 col-close">
                <a class="close btn btn-visible" href="#">&times;</a>
            </div>
        </div>
        <?php }?>
    </div>
    <a onclick="return addInput('view-columns')" href="#" class="btn btn-default btn-visible">Добавить еще</a>
    <hr>

    <h3>Выберите колонки для секции поиск</h3>
    <br/>
    <div class="row">
        <div class="col-sm-5">
            <label class="control-label">Колонка</label>
        </div>
    </div>
    <div id="search-columns" class="multiyiinput sortable">
    <?php foreach ($model->getSearchColumns() as $key => $data) {?>
        <div class="row" data-row="<?=$key?>">
            <div class="col-sm-5">
                <div class="form-group">
                    <?=Html::dropDownList("SearchColumns[$key][id_column]",$data['id_column'],$columns,['class'=>'form-control','id'=>'SearchColumns_id_column_'.$key,'placeholder'=>'Выберите колонку']);?>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <?=Html::dropDownList("SearchColumns[$key][type]",$data['type'],[
                        0=>'Выпадающий список',
                        1=>'Поиск строкой',
                        2=>'Поиск строкой, строгое соответствие',
                    ],['class'=>'form-control','id'=>'SearchColumns_type'.$key,'placeholder'=>'Выберите тип ввода']);?>
                </div>
            </div>
            <div class="col-sm-1 col-close">
                <a class="close btn btn-visible" href="#">&times;</a>
            </div>
        </div>
    <?php }?>
    </div>
    <a onclick="return addInput('search-columns')" href="#" class="btn btn-default btn-visible">Добавить еще</a>

    <br/><br/>
    <center>
        <button class="btn btn-primary" id="submit-redactor">Вставить</button>
    </center>
    <br/><br/><br/>

<?php

$json_filters = [];

foreach ($model->collection->columns as $key => $column) {
    $json_filters[] = $column->getJsonQuery();
}

$json_filters = json_encode($json_filters);

if (empty($rules))
    $rules = [['empty'=>true]];

$rules = json_encode($rules);

}?>
<?php ActiveForm::end(); ?>
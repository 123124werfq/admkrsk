<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\web\View;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Collection */
/* @var $form yii\widgets\ActiveForm */

?>

<?php $form = ActiveForm::begin(['id'=>'form-record-map']); ?>

<?php
    $columns = $collection->columns;

    $columns_coords = $columns_dropdown = [];

    foreach ($columns as $key => $column)
    {
        $columns_dropdown[$column->id_column] = $column->name;

        if ($column->isCoords())
            $columns_coords[$column->id_column] = $column->name;
    }

    $columns = $columns_dropdown;
?>

<?=$form->field($model, 'id_column_coords')->dropDownList($columns_coords);?>

<!--h3>Отображаемые поля</h3>
<br/>
<div class="row">
    <div class="col-sm-5">
        <label class="control-label">Колонка</label>
    </div>
</div>
<div id="view-columns" class="multiyiinput sortable">
    <?php /*foreach ($columns as $key => $data) {?>
    <div data-row="<?=$key?>" class="row">
        <div class="col-sm-5">
            <div class="form-group">
                <?=Html::dropDownList("ViewColumns[$key][id_column]",$data['id_column'],$columns,['class'=>'form-control','id'=>'CollectionColumn_id_column_'.$key]);?>
            </div>
        </div>
        <div class="col-sm-1 col-close">
            <a class="close btn" href="#">&times;</a>
        </div>
    </div>
    <?php break; }*/?>
</div>
<a onclick="return addInput('view-columns')" href="#" class="btn btn-default">Добавить еще</a-->
<?php ActiveForm::end(); ?>
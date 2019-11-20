<?php

use backend\widgets\UserAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use common\models\CollectionColumn;
use common\models\Collection;
use kartik\select2\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Collection */
/* @var $form yii\widgets\ActiveForm */

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


<?php $form = ActiveForm::begin(['id'=>'collection-redactor']); ?>

<?php if (empty($model->id_parent_collection)){?>
    <?= $form->field($model, 'id_parent_collection')->widget(Select2::class, [
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
    $columns = ArrayHelper::map($model->parent->columns, 'id_column', 'name');
?>
    <?=$form->field($model, 'id_parent_collection')->hiddenInput();?>

    <h3>Условия</h3>
    <br/>
    <div class="row">
        <div class="col-sm-5">
            <label class="control-label">Колонка</label>
        </div>
        <div class="col-sm-1">
            <label class="control-label">Условие</label>
        </div>
        <div class="col-sm-5">
            <label class="control-label">Значение</label>
        </div>
    </div>
    <div id="view-filters" class="multiyiinput">
        <?php foreach ($filtes as $key => $data) {?>
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <?=Html::dropDownList("ViewFilters[$key][id_column]",$data['id_column'],$columns,['required'=>true,'class'=>'form-control','id'=>'CollectionColumn_id_column'.$key]);?>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <?=Html::dropDownList("ViewFilters[$key][operator]",$data['operator'],['='=>'=','>','>=','<','<=','<>'],['required'=>true,'class'=>'form-control','id'=>'CollectionColumn_operator'.$key]);?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <?=Html::textInput("ViewFilters[$key][value]",$data['value'],['required'=>true,'class'=>'form-control','id'=>'CollectionColumn_value_'.$key,'placeholder'=>'Введите название']);?>
                    </div>
                </div>
                <div class="col-sm-1">
                    <a class="close btn" href="#">&times;</a>
                </div>
            </div>
        <?php }?>
    </div>
    <a onclick="return addInput('view-filters')" href="#" class="btn btn-default">Добавить еще</a>

    <br/><br/>
    <h3>Поля</h3>
    <br/>
    <div class="row">
        <div class="col-sm-5">
            <label class="control-label">Колонка</label>
        </div>
        <div class="col-sm-6">
            <label class="control-label">Шаблон значения</label>
        </div>
    </div>
    <div id="view-columns" class="multiyiinput sortable">
        <?php
            //$records = $model->getRecords('columns');
        ?>
        <?php foreach ($model->getViewColumns() as $key => $data) {?>
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <?=Html::dropDownList("ViewColumns[$key][id_column]",$data['id_column'],$columns,['class'=>'form-control','id'=>'CollectionColumn_id_column_'.$key,'placeholder'=>'Выберите колонку']);?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <?=Html::textInput("ViewColumns[$key][value]",$data['value'],['class'=>'form-control','id'=>'value_'.$key,'placeholder'=>'Введите шаблон']);?>
                    </div>
                </div>
                <div class="col-sm-1 col-close">
                    <a class="close btn" href="#">&times;</a>
                </div>
            </div>
        <?php }?>
    </div>
    <a onclick="return addInput('view-columns')" href="#" class="btn btn-default">Добавить еще</a>

    <br/><br/>
    <center>
        <button class="btn btn-primary" id="submit-redactor">Вставить</button>
    </center>
    <br/><br/><br/>

    <script>
    document.getElementById('submit-redactor').addEventListener('click', function (event) {
        $form = $("#collection-redactor");

        var origin = '<?=$_SERVER["HTTP_REFERER"]?>';

        $.ajax({
            url: $form.attr('action'),
            type: 'post',
            dataType:'json',
            data: $form.serialize()+'&json=1',
            success: function(data)
            {
                window.parent.postMessage({
                    mceAction: 'execCommand',
                    cmd: 'iframeCommand',
                    value: data
                }, origin);
            }
        });

        event.preventDefault();
   });
</script>
<?php }?>


<?php ActiveForm::end(); ?>
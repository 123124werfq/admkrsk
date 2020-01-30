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
    'not'=>'Не пусто',
    '<>'=>'Не равно',
];

if (empty($filtes))
    $filtes = [
        [
            'id_column'=>'',
            'operator'=>'',
            'value'=>'',
        ]
    ];

if (empty($search))
    $search = [
        [
            'id_column'=>'',
            'type'=>0,
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
    $columns = $model->parent->columns;

    //$columns_coords = [];
    $columns_dropdown = [];

    foreach ($columns as $key => $column) {
        $columns_dropdown[$column->id_column] = $column->name;
    }

    $columns = $columns_dropdown;
?>
    <?=$form->field($model, 'id_parent_collection',['template'=>'{input}'])->hiddenInput();?>

    <?=$form->field($model, 'template_view')->dropDownList(['table'=>'Таблицей','template'=>'Шаблоном'])->hint('Если не заполнен шаблон вывода элемента, то выведятся все данные из колонок отображения в виде списка');?>

    <?=$form->field($model, 'pagesize')->textInput(['type'=>'number','step'=>1,'min'=>1]);?>
    <div class="row">
        <div class="col-md-3">
            <?=$form->field($model, 'show_row_num')->checkBox();?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'show_column_num')->checkBox();?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'show_on_map')->checkBox();?>
        </div>
        <div class="col-md-3">
            <?=$form->field($model, 'show_download')->checkBox();?>
        </div>
    </div>

    <?=$form->field($model, 'id_group')->dropDownList($columns,['prompt'=>'Выберите колонку для группировки']);?>

    <?=$form->field($model, 'link_column')->dropDownList($columns,['prompt'=>'Выберите колонку для ссылки на подробную информацию']);?>

    <div class="row">
        <div class="col-md-6">
            <?=$form->field($model, 'id_column_order')->dropDownList($columns,['prompt'=>'Выберите колонку для сортировки']);?>
        </div>
        <div class="col-md-6">
            <?=$form->field($model, 'order_direction')->dropDownList([SORT_ASC=>'По возрастанию',SORT_DESC=>'По убыванию']);?>
        </div>
    </div>

    <hr>

    <h3>Условия фильтрации</h3>

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
                        <?=Html::dropDownList("ViewFilters[$key][id_column]",$data['id_column'],$columns,['required'=>true,'class'=>'form-control','id'=>'CollectionColumn_id_column'.$key,'prompt'=>'Выберите колонку']);?>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <?=Html::dropDownList("ViewFilters[$key][operator]",$data['operator'],$operators,['required'=>true,'class'=>'form-control','id'=>'CollectionColumn_operator'.$key]);?>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="form-group">
                        <?=Html::textInput("ViewFilters[$key][value]",$data['value'],['required'=>true,'class'=>'form-control','id'=>'CollectionColumn_value_'.$key,'placeholder'=>'Введите значение']);?>
                    </div>
                </div>
                <div class="col-sm-1">
                    <a class="close btn" href="#">&times;</a>
                </div>
            </div>
        <?php }?>
    </div>
    <a onclick="return addInput('view-filters')" href="#" class="btn btn-default">Добавить еще</a>

    <hr>
    <h3>Поля</h3>
    <br/>
    <div class="row">
        <div class="col-sm-5">
            <label class="control-label">Колонка</label>
        </div>
    </div>
    <div id="view-columns" class="multiyiinput sortable">
        <?php foreach ($model->getViewColumns() as $key => $data) {?>
        <div class="row">
            <div class="col-sm-5">
                <div class="form-group">
                    <?=Html::dropDownList("ViewColumns[$key][id_column]",$data['id_column'],$columns,['class'=>'form-control','id'=>'CollectionColumn_id_column_'.$key]);?>
                </div>
            </div>
            <div class="col-sm-6">
                <label>
                    <?=Html::checkBox("showdetails",'',['class'=>'showdetails','id'=>'CollectionColumn_showdetails_'.$key]);?>

                    опции
                </label>
            </div>
            <div class="col-sm-1 col-close">
                <a class="close btn" href="#">&times;</a>
            </div>
            <div class="col-sm-12 hide flex">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <?=Html::textInput("ViewColumns[$key][group]",$data['options']['group']??'',['class'=>'form-control','id'=>'CollectionColumn_group_'.$key,'placeholder'=>'Введите группу']);?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <?=Html::dropDownList("ViewColumns[$key][show_for_searchcolumn]",$data['show_for_searchcolumn'],$columns,['class'=>'form-control','id'=>'CollectionColumn_show_for_searchcolumn_'.$key,'prompt'=>'Показывать если введено']);?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php break; }?>
    </div>
    <a onclick="return addInput('view-columns')" href="#" class="btn btn-default">Добавить еще</a>
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
        <div class="row">
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
                <a class="close btn" href="#">&times;</a>
            </div>
        </div>
    <?php }?>
    </div>
    <a onclick="return addInput('search-columns')" href="#" class="btn btn-default">Добавить еще</a>

    <?=$form->field($model, 'table_head')->textArea()->hint('Заполняется для нестандартных шапок таблиц. Количество колонок должно совпадать с количеством отображаемых столбцов');?>

    <?=$form->field($model, 'table_style')->textArea();?>

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
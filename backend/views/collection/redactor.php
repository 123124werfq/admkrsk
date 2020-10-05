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

$model->filters = $rules = $model->getViewFilters();
$model->filters = json_encode($model->filters);
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
                'data' => new JsExpression('function(params) { return {q:params.term,id_type:'.(int)$id_type.'}; }')
            ],
        ],
    ])->label('Выберите список')?>
<?php
}
else
{
    $columns = $model->parent->columns;

    $columns_dropdown = [];

    $columns_file = [];

    foreach ($columns as $key => $column)
    {
        $columns_dropdown[$column->id_column] = $column->name;

        if ($column->isFile())
            $columns_file[$column->id_column] = $column->name;
    }

    $columns = $columns_dropdown;
?>
    <?=$form->field($model, 'id_parent_collection',['template'=>'{input}'])->hiddenInput();?>

    <?php if ($model->parent->id_type == 1){?>
        <?=$form->field($model, 'id_form')->dropDownList(ArrayHelper::map($model->parent->forms, 'id_form', 'name'),['prompt'=>'Выберите форму для ввода']);?>
    <?php }?>

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
        <div data-row="<?=$key?>" class="row">
            <div class="col-sm-5">
                <div class="form-group">
                    <?=Html::dropDownList("ViewColumns[$key][id_column]",$data['id_column'],$columns,['class'=>'form-control','id'=>'CollectionColumn_id_column_'.$key]);?>
                </div>
            </div>
            <div class="col-sm-6">
                <label>
                    <?=Html::checkBox("ViewColumns[$key][showdetails]",$showDetail,['class'=>'showdetails','id'=>'CollectionColumn_showdetails_'.$key]);?>
                    опции
                </label>

                <div class="options <?= $showDetail? '' : 'hide'?>"?>
                    <div class="form-group">
                        <?=Html::textInput("ViewColumns[$key][class]",$data['class']??'',['class'=>'form-control','id'=>'CollectionColumn_class_'.$key,'placeholder'=>'Класс/Стиль колонки']);?>
                    </div>
                    <div class="form-group">
                        <?=Html::textInput("ViewColumns[$key][group]",$data['group']??'',['class'=>'form-control','id'=>'CollectionColumn_group_'.$key,'placeholder'=>'Введите группу']);?>
                    </div>
                    <div class="form-group">
                        <?=Html::dropDownList("ViewColumns[$key][show_for_searchcolumn]",$data['show_for_searchcolumn'],$columns,['class'=>'form-control','id'=>'CollectionColumn_show_for_searchcolumn_'.$key,'prompt'=>'Показывать если введено','multiple'=>true]);?>
                    </div>
                    <?php if (!empty($columns_file)){?>
                    <div class="form-group">
                        <?=Html::dropDownList("ViewColumns[$key][filelink]",$data['filelink']??'',$columns_file,['class'=>'form-control','id'=>'CollectionColumn_filelink_'.$key,'prompt'=>'Ссылка на файл']);?>
                    </div>
                    <?php }?>
                </div>
            </div>
            <div class="col-sm-1 col-close">
                <a class="close btn" href="#">&times;</a>
            </div>
        </div>
        <?php }?>
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
                        3=>'Поиск по дате',
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

    <h3>Условия фильтрации</h3>

    <?=$form->field($model, 'filters')->hiddenInput()->label(false);?>

    <div id="querybuilder"></div>

    <?=''//$form->field($model, 'table_head')->textArea()->hint('Заполняется для нестандартных шапок таблиц. Количество колонок должно совпадать с количеством отображаемых столбцов');?>

    <?=$form->field($model, 'table_style')->textArea();?>

    <?= $form->field($model, 'download_columns')->widget(Select2::class, [
        'data' => $columns_dropdown,
        'pluginOptions' => [
            'multiple' => true,
            'allowClear' => true,
            'minimumInputLength' => 0,
            'placeholder' => 'Начните ввод',
        ],
    ])->label('Колонки для скачивания')->hint('Если не указано то будут поля для отображения')?>

    <br/><br/>
    <center>
        <button class="btn btn-primary" id="submit-redactor"><?= $model->isEdit ? 'Изменить' : 'Вставить'?></button>
    </center>
    <br/><br/><br/>
    <script>
        document.getElementById('submit-redactor').addEventListener('click', function (event) {

                var rules = $('#querybuilder').queryBuilder('getMongo');
                $("#collection-filters").val(JSON.stringify(rules));

                $form = $("#collection-redactor");

                var origin = '<?= isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '' ?>';
                let url = "<?= $model->isEdit ? '&configureEditCollection=1' : '&json=1' ?>";
                $.ajax({
                    url: $form.attr('action'),
                    type: 'post',
                    dataType:'json',
                    data: $form.serialize() + url,
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
<?php

$json_filters = [];

foreach ($model->parent->columns as $key => $column) {
    $json_filters[] = $column->getJsonQuery();
}

$json_filters = json_encode($json_filters);
$json_filters = str_replace('"datafunction"', 'function(params) { return {q:params.term};}', $json_filters);

if (empty($rules))
    $rules = [['empty'=>true]];

$rules = json_encode($rules);

$script = <<< JS

    $("#collection-redactor").submit(function(){
        var rules = $('#querybuilder').queryBuilder('getMongo');
        $("#collection-filters").val(JSON.stringify(rules));
    });

    $('#querybuilder').queryBuilder({
      lang_code: 'ru',
      filters: $json_filters,
    });

    if ('$rules'!='' && '$rules'!='[{"empty":true}]')
        $('#querybuilder').queryBuilder('setRulesFromMongo',$rules);
JS;

    $this->registerJs($script, View::POS_END);
}?>
<?php ActiveForm::end(); ?>
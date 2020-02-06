<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;
    use yii\data\ArrayDataProvider;
    use yii\helpers\ArrayHelper;

    use common\models\CollectionColumn;;

    $this->title = 'Импорт XLS, CSV';
    $this->params['breadcrumbs'][] = $this->title;

    $types = [
        CollectionColumn::TYPE_INPUT=>'',
        CollectionColumn::TYPE_INTEGER=>'',
        CollectionColumn::TYPE_DATE=>'',
        CollectionColumn::TYPE_DATETIME=>'',
        CollectionColumn::TYPE_TEXTAREA=>'',
        CollectionColumn::TYPE_FILE_OLD=>'',
        CollectionColumn::TYPE_SELECT=>'',
    ];

    foreach ($types as $key => $type)
        $types[$key] = CollectionColumn::getTypeLabel($key);

    $existColumns = [];

    if (!empty($existCollection))
    {
        $existColumnsModels = $existCollection->getColumns()->where(['type'=>array_keys($types)])->all();
        $existColumns = ArrayHelper::map($existColumnsModels, 'id_column', 'name');
        $existColumns[-1] = 'Не импортировать';

        //$existColumns[0] = 'Создать новую колонку';
    }
?>

 <?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'template' => '<div class="row">{label}<div class="col-sm-8">{input}{hint}{error}</div></div>',
        'labelOptions' => ['class' => 'col-sm-2 control-label'],
    ],
    'options'=>[
        "enctype"=>"multipart/form-data",
    ]
]); ?>

<?=Html::activeHiddenInput($model, 'filepath')?>
<?=Html::activeHiddenInput($model, 'name');?>
<?=Html::activeHiddenInput($model, 'sheet');?>

<div class="ibox m-t">
    <div class="ibox-content">

        <h2>Настройка колонок</h2>

        <br/>

        <table class="table">
            <tr>
                <th>Название</th>
                <th>Алиас</th>
                <th>Тип</th>
                <?php if (!empty($existCollection)){?>
                <th>Импортировать в</th>
                <?php }?>
            </tr>
            <?php foreach ($columns as $key => $column) {?>
            <tr>
                <td><?=Html::textInput('CollectionImportForm[columns]['.$key.'][name]',$column,['class'=>'form-control','required'=>true])?></td>
                <td><?=Html::textInput('CollectionImportForm[columns]['.$key.'][alias]',$keys[$key],['class'=>'form-control','required'=>true])?></td>
                <td><?=Html::dropDownList('CollectionImportForm[columns]['.$key.'][type]','',$types,['class'=>'form-control','required'=>true])?></td>
                <?php if (!empty($existCollection)){
                    $id_find_column = 0;
                    foreach ($existColumnsModels as $ekey => $ecol) {
                        if ($ecol->alias == $keys[$key])
                        {
                            $id_find_column = $ecol->id_column;
                            break;
                        }
                    }
                ?>
                    <td><?=Html::dropDownList('CollectionImportForm[columns]['.$key.'][id_column]',$id_find_column,$existColumns,['prompt'=>'+ Новая колонка','class'=>'form-control'])?></td>
                <?php }?>
            </tr>
            <?php }?>
        </table>

        <?=Html::submitButton('Импортировать', ['class' => 'btn btn-primary','name'=>'import','value'=>1]) ?>
    </div>
</div>

<?php ActiveForm::end();?>

<div class="ibox m-t">
    <div class="ibox-content">
        <table class="table">
            <tr>
            <?php foreach ($columns as $rkey => $col) {?>
                <th><?=$col?></th>
            <?php }?>
            </tr>
        <?php foreach ($records as $rkey => $row) {?>
            <tr>
                <?php
                    foreach ($row as $tkey => $td)
                        echo "<td>".Html::encode($td)."</td>";
                ?>
            </tr>
        <?php
            if ($rkey>6)
                break;
            }
        ?>
        </table>
    </div>
</div>
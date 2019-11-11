<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;
    use yii\data\ArrayDataProvider;

    $this->title = 'Импорт XML';
    $this->params['breadcrumbs'][] = $this->title;

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


<?php if (empty($table)){?>

<div class="ibox m-t">
    <div class="ibox-content">
        <div class="fileinput fileinput-new input-group" data-provides="fileinput">
            <div class="form-control" data-trigger="fileinput">
                <i class="glyphicon glyphicon-file fileinput-exists"></i>
            <span class="fileinput-filename"></span>
            </div>
            <span class="input-group-addon btn btn-default btn-file">
                <span class="fileinput-new">Выбрать файл</span>
                <span class="fileinput-exists">Изменить</span>
                <?=Html::activeFileInput($model, 'file',['accept'=>".xls,.xlsx,.csv"])?>
            </span>
            <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Удалить</a>
        </div>

        <div class="hr-line-dashed"></div>

        <div class="row">
            <div class="col-md-6">
                <?=Html::activeTextInput($model, "name",['placeholder'=>'Название списка','class'=>'form-control','required'=>true])?>
            </div>
            <div class="col-md-5 text-right">
                <?=Html::submitButton('Импортировать', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    </div>
</div>
<?php }else echo Html::activeHiddenInput($model, 'name');?>


<?php if (!empty($table)){
    /*$dataProvider = new ArrayDataProvider([
        'allModels' => $cvs,
        'pagination' => [
            'pageSize' => 9999,
        ],
    ]);*/
?>
    <div class="ibox m-t">
        <div class="ibox-content">
           <div class="tabs-container">
                <ul class="nav nav-tabs" role="tablist">
                    <?php
                        $i = 0;
                        foreach ($table as $sheetname => $sheet) {?>
                        <li class="<?=$i==0?'active':''?>"><a class="nav-link" data-toggle="tab" href="#tab-<?=$i?>"><?=$sheetname?></a></li>
                    <?php $i++; }?>
                </ul>
                <div class="tab-content">
                    <?php
                        $i = 0;
                        foreach ($table as $sheetname => $sheet) {?>
                        <div role="tabpanel" id="tab-<?=$i?>" class="tab-pane <?=$i==0?'active':''?>">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <?=Html::activeTextInput($model, "[$i]skip",['placeholder'=>'Начать со строки','class'=>'form-control import-collection-start','type'=>'number','min'=>0])?>
                                    </div>
                                    <div class="col-sm-3">
                                        <?=Html::activeCheckBox($model, "[$i]firstRowAsName")?>
                                    </div>
                                    <div class="col-sm-4">
                                        <?=Html::submitButton('Импортировать', ['class' => 'btn btn-primary','value'=>$sheetname,'name'=>'CollectionImportForm[sheet]']) ?>
                                    </div>
                                </div>
                                <br/>
                                <div class="table-responsive">
                                    <table class="table">
                                    <?php foreach ($sheet as $rkey => $row) {?>
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
                        </div>
                    <?php $i++; }?>
                </div>
            </div>
        </div>
    </div>
<?php }?>

<?php ActiveForm::end();?>
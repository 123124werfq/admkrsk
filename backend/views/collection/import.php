<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\grid\GridView;
    use yii\data\ArrayDataProvider;

    $this->title = 'Импорт XLS, CSV';
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

<?php
if (empty($table))
{
?>
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
<?php }
else
{
    echo Html::activeHiddenInput($model, 'name');

    if (!empty($table))
        echo $this->render('_import_table',[
            'table'=>$table,
            'model'=>$model
        ]);
}
?>
<?php ActiveForm::end();?>
<?php
    use yii\helpers\Html;
    use common\models\Collection;
    use common\models\CollectionRecord;
    use yii\helpers\ArrayHelper;
    use backend\widgets\MapInputWidget;
?>
<?=Html::hiddenInput("CollectionRecord[id_record]",$model->id_record);?>

<?php 
foreach ($collection->getColumns()->indexBy('id_column')->all() as $ckey => $column) {?>
    <div class="row form-group">
        <div class="col-sm-2 text-right">
            <label style="padding-top: 10px;" class="control-label" for="page-id_parent"><?=$column->name?></label>
        </div>
        <div class="col-sm-10">
        <?php
            switch ($column->type) {
                case $column::TYPE_INPUT:
                    echo Html::textInput("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$column->name]);
                    break;
                case $column::TYPE_INTEGER:
                    echo Html::textInput("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$column->name,'type'=>'number']);
                    break;
                case $column::TYPE_DATE:
                    echo Html::textInput("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$column->name,'type'=>'date']);
                    break;
                case $column::TYPE_DATETIME:
                    echo Html::textInput("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$column->name,'type'=>'datetime-local']);
                    break;
                case $column::TYPE_TEXTAREA:
                    echo Html::textArea("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$column->name]);
                    break;
                case $column::TYPE_SELECT:
                    echo Html::dropDownList("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',explode(',', $column->variables),['class'=>'form-control','id'=>'Value_'.$ckey,'prompt'=>$column->name]);
                    break;
                case $column::TYPE_RICHTEXT:
                    echo Html::textArea("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$column->name,'redactor'=>true]);
                    break;
                /*case $column::TYPE_MULTISELECT:
                    echo Html::dropDownList("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',$column->variables,['class'=>'form-control','id'=>'Value_'.$ckey,'prompt'=>$column->name,'multyple'=>true]);
                    break;*/
                case $column::TYPE_MAP:
                    //echo Html::textInput("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$column->name]);
                    echo MapInputWidget::widget(['name' => 'CollectionRecord', 'index' => $ckey]);
                    break;
                case $column::TYPE_FILE:
                    echo Html::fileInput("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$column->name]);
                    break;
                case $column::TYPE_IMAGE:
                    echo Html::fileInput("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$column->name]);
                    break;
                case $column::TYPE_COLLECTION:
                    $records = CollectionRecord::find()->where(['id_collection'=>(int)$column->variables])->all();

                    echo Html::dropDownList("CollectionRecord[$ckey]",(isset($data[$ckey]))?$data[$ckey]:'',ArrayHelper::map($records, 'id_record', 'lineValue'),['class'=>'form-control','id'=>'Value_'.$ckey,'prompt'=>$column->name]);
                    break;
            }
        ?>
        </div>
    </div>
    <?php }?>
</div>
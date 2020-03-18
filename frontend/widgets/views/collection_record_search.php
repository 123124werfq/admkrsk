<?php
    use yii\helpers\Html;
    use \common\models\CollectionRecord;
    use \common\models\CollectionColumn;
?>

<form data-hash="<?=$unique_hash?>" action="" method="GET">
    <?php if (!empty($search_columns)){?>
        <?php foreach ($search_columns as $key => $column)
        {
            if ($column['type']==0)
                echo Html::dropDownList('search_column['.$unique_hash.']['.$column['column']->id_column.']','',$column['values'],['class'=>'form-control','prompt'=>$column['column']->name]);
            else
                echo Html::textInput('search_column['.$unique_hash.']['.$column['column']->id_column.']','',['class'=>'form-control','placeholder'=>$column['column']->name,'max-lenght'=>255]);

            echo '';
         }?>
    <?php }?>

    <button type="submit" class="button2">Найти</button>
</form>

<?php if (!empty($Record)){?>
<br/><br/><br/>
<table width="100%"  class="table table-striped table-hover">
<?php
    foreach ($columns as $column){
?>
    <tr>
        <td width="200"><?=$column->name?></td>
        <td>
            <?php
                if (!isset($Record[$column->id_column]))
                    echo '';
                else
                {
                    if ($column->isRelation())
                    {
                        foreach ($Record[$column->id_column] as $id_subrecord => $subrecord)
                        {
                            echo \frontend\widgets\CollectionRecordWidget::widget([
                                'collectionRecord'=>CollectionRecord::findOne($id_subrecord),
                            ]);
                        }
                    }
                    else
                    {

                        echo $column->getValueByType($Record[$column->id_column]);
                    }
                }
            ?>
        </td>
    </tr>
<?php
    }
?>
</table>
<?php }?>
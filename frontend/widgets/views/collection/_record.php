<?php
    use \common\models\CollectionRecord;
    use \common\models\CollectionColumn;
?>
<table width="100%"  class="table table-striped table-hover">
<?php
    foreach ($columns as $column){
?>
    <tr>
        <td width="200"><?=$column->name?></td>
        <td>
            <?php
                if (!isset($recordData[$column->id_column]))
                    echo 'Не заполнено';
                else
                {
                    if ($column->isRelation())
                    {
                        foreach ($recordData[$column->id_column] as $id_subrecord => $subrecord)
                        {
                            echo \frontend\widgets\CollectionRecordWidget::widget([
                                'collectionRecord'=>CollectionRecord::findOne($id_subrecord),
                            ]);
                        }
                    }
                    else
                        echo $column->getValueByType($recordData[$column->id_column]);
                }
            ?>
        </td>
    </tr>
<?php
    }
?>
</table>
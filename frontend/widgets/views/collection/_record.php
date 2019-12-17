<?php
    use \common\models\CollectionRecord;
    use \common\models\CollectionColumn;
?>
<table width="100%">
<?php
    foreach ($columns as $column){
?>
    <tr>
        <td width="200"><?=$column->name?></td>
        <td>
            <?php
                if (!isset($Record[$column->id_column]))
                    echo 'Не заполнено';
                else
                {
                    if ($column->type==CollectionColumn::TYPE_CHECKBOX)
                    {
                        if (empty($Record[$column->id_column]))
                            echo 'Да';
                        else
                            echo 'Нет';
                    }
                    else if ($column->type==CollectionColumn::TYPE_DATE)
                    {
                        echo date('d.m.Y',$Record[$column->id_column]);
                    }
                    else if ($column->type==CollectionColumn::TYPE_COLLECTIONS)
                    {
                        $records = CollectionRecord::find()->where(['id_record'=>array_keys($Record[$column->id_column])])->all();

                        foreach ($records as $dkey => $data)
                            echo frontend\widgets\CollectionRecordWidget::widget(['collectionRecord'=>$data]);
                    }
                    else if (is_array($Record[$column->id_column]))
                    {
                        echo implode('<br>', $Record[$column->id_column]);
                    }
                    else
                        echo $Record[$column->id_column];
                }

            ?>
        </td>
    </tr>
<?php
    }
?>
</table>
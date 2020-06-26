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
                        if (!empty($recordData[$column->id_column]) && is_array($recordData[$column->id_column]))
                            foreach ($recordData[$column->id_column] as $id_subrecord => $subrecord)
                            {
                                echo \frontend\widgets\CollectionRecordWidget::widget([
                                    'collectionRecord'=>CollectionRecord::findOne($id_subrecord),
                                ]);
                            }
                    }
                    elseif ($column->type == CollectionColumn::TYPE_JSON)
                    {
                        $array = $column->getValueByType($recordData[$column->id_column]);

                        $ths = json_decode($column->input->values, true);

                        if (!is_array($columns) && !empty($columns))
                            echo json_encode($array);
                        else
                        {
                            echo '<table><tr>';
                            foreach ($ths as $key => $th)
                                echo '<th ' . (!empty($th['width']) ? 'style="width:' . $th['width'] . '%"' : '') . ' >' . $th['name'] . '</th>';
                            echo '</tr>';
                            foreach ($array as $key => $row)
                            {
                                echo '<tr>';
                                foreach ($row as $key => $value)
                                    echo '<td>'.$value.'</td>';
                                echo '</tr>';
                            }

                            echo '</tr>';
                            echo '</table>';
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
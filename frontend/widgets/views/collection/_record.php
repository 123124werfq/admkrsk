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
                        if (!in_array($column->input->id_collection,$recursionCollections))
                        {
                            if (!empty($recordData[$column->id_column]) && is_array($recordData[$column->id_column]))
                                foreach ($recordData[$column->id_column] as $id_subrecord => $subrecord)
                                {
                                    echo \frontend\widgets\CollectionRecordWidget::widget([
                                        'collectionRecord'=>CollectionRecord::find()->where(['id_record'=>$id_subrecord['id_record']??$id_subrecord])->one(),
                                        'recursionCollections'=>array_merge($recursionCollections,[$column->id_collection])
                                    ]);
                                }
                        }
                    }
                    elseif ($column->type == CollectionColumn::TYPE_JSON)
                    {
                        $values = $column->getValueByType($recordData[$column->id_column]);

                        $ths = json_decode($column->input->values, true);

                        if (!is_array($values) || empty($values))
                            echo json_encode($values);
                        else
                        {
                            echo '<table><tr>';
                            foreach ($ths as $key => $th)
                                echo '<th ' . (!empty($th['width']) ? 'style="width:' . $th['width'] . '%"' : '') . ' >' . $th['name'] . '</th>';
                            echo '</tr>';

                            foreach ($values as $key => $row)
                            {
                                echo '<tr>';
                                foreach ($row as $vkey => $value)
                                    echo '<td>'.$value.'</td>';
                                echo '</tr>';
                            }

                            echo '</tr>';
                            echo '</table>';
                        }
                    }
                    else
                    {
                        $value = $column->getValueByType($recordData[$column->id_column]);

                        if (is_array($value))
                            var_dump($value);

                        else echo $value;
                    }
                }
            ?>
        </td>
    </tr>
<?php
    }
?>
</table>
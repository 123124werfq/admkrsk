<?php 
    use yii\helpers\Html;
?>
<table class="form-table">
<thead>
<tr>
    <?php foreach ($columns as $key => $column) {
        echo '<th ' . (!empty($column['width']) ? 'style="width:' . $column['width'] . '%"' : '') . ' >' . $column['name'] . '</th>';
    } ?>
    <th></th>
</tr>
</thead>
<tbody id="table<?=$id_input?>">
<?php
    foreach ($data as $rkey => $row)
    {
        echo '<tr data-row="'.$rkey.'">';
        $i = 0;
        foreach ($columns as $ckey => $column)
        {
            $alias = $column['alias'];

            if (!empty($column['type']) && $column['type']=='list')
            {
                $values = [];

                foreach ((!empty($column['values']))?explode(';', $column['values']):[] as $vkey => $value)
                    $values[$value] = $value;

                echo '<td '.(!empty($column['width'])?'width="'.$column['width'].'"':'').'>'.Html::dropDownList($inputname.'['.$rkey.']['.$alias.']',$row[$alias]??'',$values,['id'=>'input'.$input->id_input.'_col','class'=>"form-control"]).'</td>';
            }
            else
                echo '<td '.(!empty($column['width'])?'width="'.$column['width'].'"':'').'>'.Html::textInput($inputname.'['.$rkey.']['.$alias.']',$row[$alias]??'',['type'=>$column['type'],'id'=>'input'.$input->id_input.'_col','class'=>"form-control"]).'</td>';
            $i++;
        }
        echo '<td width="10" class="td-close">
                    <a class="close" onclick="return removeRow(this)" href="javascript:">&times;</a>
                </td>
                </tr>';
    }
?>
</tbody>
<tfoot>
<td colspan="<?= count($columns) + 1 ?>">
    <a onclick="addInput('table<?=$id_input?>')" class="btn btn__gray btn-default" href="javascript:">Добавить</a>
</td>
</tfoot>
</table>
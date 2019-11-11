<?php use yii\helpers\Html;?>
<tr id="record<?=$id_record?>">
    <td class="button-column">
        <a class="update-record" href="/collection/record?id=<?=$model->id_collection?>&id_record=<?=$id_record?>" title="Редактировать" aria-label="Редактировать">
            <span class="glyphicon glyphicon-pencil"></span></a>
        <a class="delete-record" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" href="/collection/delete-record?id=<?=$id_record?>" title="Удалить"><span class="glyphicon glyphicon-trash"></span></a>
    </td>
    <?php foreach ($columns as $ckey => $column){?>
    <td>
        <?php if (isset($row[$ckey]))
        {
            $data = Html::encode($row[$ckey]);
            if (strlen($data)>300)
                echo '[Текстовые данные]';
            else
                echo $data;
        }
        ?>
    </td>
    <?php }?>
</tr>
<?php
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;
    $records = $model->links;

    $levels = [];
    foreach ($records as $key => $record)
        $levels[$record->id_parent][$record->id_link] = $record;
?>
<div class="multiinput sortable dropable">
    <?php foreach ($levels as $key => $data) {?>
        <div class="col-sm-3">
            <a class="menu-item" data-id="<?=$data->id_link?>" href="javascript:"><?=$data->name?></a>
            <div class="menu-childs">
                <?php foreach ($data->childs as $ckey => $child) {?>
                    <a class="menu-item" data-id="<?=$data->id_link?>" href="javascript:"><?=$data->name?></a>
                <?php }?>
            </div>
        </div>
    <?php }?>
</div>

<a class="btn btn-default" onclick="return addInput('list-records')" href="#">Добавить еще</a>
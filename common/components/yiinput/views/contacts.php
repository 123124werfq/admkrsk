<div class="row">
    <div class="multiyiinput col-md-8 sortable" id="yiinput-<?=$relation?>" data-selector=".form-inline">
<?php
    use yii\helpers\Html;

    $i = 0;
    if (is_array($data))
    {
        foreach ($data as $rkey=>$record)
        {
            $class_name = get_class($record);
            echo '<div class="form-inline">';

            $i++;

            foreach ($fields as $key=>$field)
            {
                if (isset($field['params']['type']) && $field['params']['type']=='hidden')
                    echo Html::activeHiddenInput($record,"[$relation][$rkey]$key");
                else
                {
                    echo Html::activeTextInput($record,"[$relation][$rkey]$key",(!empty($field['params']))?$field['params']:[]).' ';
                }
            }

            // закрывающий элемент
            if (!$single)
                echo '<a class="close btn btn-default" href="#" onclick="return removeInput(this)">&times;</a>';

            echo '</div>';
        }
    }
?>
    </div>
    <div class="col-md-4">
        <a class="btn btn-info" href="javascript:" onclick="return addInput('yiinput-<?=$relation?>')"><?=$button?></a>
    </div>
</div>
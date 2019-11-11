<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Collection;
use common\models\CollectionColumn;
?>
<div class="form-group">
    <?php
    switch ($data->type) {
        case CollectionColumn::TYPE_SELECT:
            echo Html::textInput("CollectionColumn[columns][$key][variables]",$data->variables,['class'=>'form-control','id'=>'CollectionColumn_variables'.$key,'placeholder'=>'Введите возможные значения']);
            break;
        case CollectionColumn::TYPE_FILE:
            echo Html::textInput("CollectionColumn[columns][$key][variables][]",$data->variables,['class'=>'form-control','id'=>'CollectionColumn1_variables'.$key,'placeholder'=>'Введите возможные расширения']);
            break;
            echo Html::textInput("CollectionColumn[columns][$key][variables][]",$data->variables,['class'=>'form-control','id'=>'CollectionColumn2_variables'.$key,'type'=>,'placeholder'=>'Максимальное количество файлов']);
            break;
        case CollectionColumn::TYPE_COLLECTION:
            echo Html::dropDownList("CollectionColumn[columns][$key][variables]",$data->variables,ArrayHelper::map(Collection::find()->all(), 'id_collection', 'name'),['class'=>'form-control','id'=>'CollectionColumn_variables'.$key,'placeholder'=>'Введите возможные значения']);
            break;
    }?>
</div>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Collectionrecord */

$this->title = 'История изменений '.$model->id_record;
$this->params['breadcrumbs'][] = ['label' => 'Списки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->record->collection->name, 'url' => ['collection-record/index','id'=>$model->record->id_collection]];
$this->params['breadcrumbs'][] = ['label' => $model->id_record, 'url' => ['collection-record/view','id'=>$model->record->id_record]];
?>
<div class="ibox">
    <div class="ibox-content">
        <table class="table table-hovered">

        <?php foreach ($columns as $key=>$column){

            echo '<tr>';
            echo '<td>'.$column->name.'</td>';
            echo '<td>';
            if (isset($data['col'.$key.'_search']))
                echo $data['col'.$key.'_search'];
            else if (isset($data['col'.$key]))
            {
                if (is_array($data['col'.$key]))
                    echo implode(',', $data['col'.$key]);
                else
                    echo $data['col'.$key];
            }
            echo '</td>';
            echo '</tr>';
        }?>
        </table>
    </div>
</div>
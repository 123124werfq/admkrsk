<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заявки по муниципальным услугам';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-appeal-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id_appeal:text:#',
            'target.reestr_number',
            'target.service.name',
            //'data:ntext',
            //'archive',
            'created_at:date:Создано',
            //'created_by',
            //'updated_at',
            //'updated_by',
            //'deleted_at',
            //'deleted_by',
            //'id_record',
            //'id_collection',
            //'number_internal:ntext',
            //'number_system',
            //'number_common',
            //'id_target:ntext',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['class'=>'button-column']
            ],
        ],
        'tableOptions'=>[
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover'
        ],
    ]); ?>


</div>

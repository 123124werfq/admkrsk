<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Цели';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="service-target-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id_target',
            'service.name',
            'name',
            'reestr_number',
            //'old',
            //'modified_at',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            //'deleted_at',
            //'deleted_by',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['class'=>'button-column']
            ],
        ],
        'tableOptions'=>[
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover'
        ]
    ]); ?>
</div>

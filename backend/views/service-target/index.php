<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Service Targets';
$this->params['breadcrumbs'][] = $this->title;

Html::a('Добавить цель', ['create'], ['class' => 'btn btn-success']);
?>
<div class="service-target-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_target',
            'id_service',
            'id_form',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

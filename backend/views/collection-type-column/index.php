<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Колонки типов списков';
    $this->params['breadcrumbs'][] = $this->title;

    $this->params['button-block'][] = Html::a('Добавить', ['create','id'=>$id_type], ['class' => 'btn btn-success']);
?>

<div class="ibox">
        <div class="ibox-content">
        <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id_column',
            'name',
            'type',
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
</div>

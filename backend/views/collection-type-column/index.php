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
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'contentOptions' => ['class' => 'button-column'],
            ]
        ],
        'tableOptions' => [
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover',
            'id' => 'grid',
        ]
    ]); ?>
</div>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\GalleryGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Группы галлерей';
$this->params['breadcrumbs'][] = $this->title;
$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
?>
<div class="ibox">
        <div class="ibox-content">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'id'
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['class' => 'button-column']
            ],
        ],
        'tableOptions' => [
            'emptyCell ' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover',
            'id' => 'grid',
        ]
    ]); ?>
</div>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Типы списков';
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
?>
<div class="collection-type-index">
    <div class="ibox">
        <div class="ibox-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id_type',
                'name',
                'is_faq:boolean',
                'created_at:date',

                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
        </div>
    </div>
</div>
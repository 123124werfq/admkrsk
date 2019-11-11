<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AlertSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Всплывающие сообщения';
$this->params['breadcrumbs'][] = $this->title;
$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    'columns' => [
        'id_alert',
        'page.title:text:Раздел',
        'state',
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

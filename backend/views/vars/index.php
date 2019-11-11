<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Переменные';
$this->params['breadcrumbs'][] = $this->title;
if (Yii::$app->user->can('admin.vars')) {
    $this->params['button-block'][] = Html::a('Добавить переменную', ['create'],
        ['class' => 'btn btn-primary pull-right']);
}
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id_var',
        'name',
        'alias',
        'content:ntext',
        ['class' => 'yii\grid\ActionColumn',
         'template' => '<span class="btn btn-default">{update}</span> <span class="btn btn-default">{delete}</span>',
         'contentOptions'=>['class'=>'button-column']
        ]
    ],
    'tableOptions'=>[
        'class'=>'panel table table-striped ids-style valign-middle'
    ]
]); ?>
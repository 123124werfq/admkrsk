<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

if (empty($dataProvider))
{
    $dataProvider = new ActiveDataProvider([
        'query' => \common\models\CollectionColumn::find()->where(['id_collection'=>$id_collection]),
    ]);
}
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Столбцы';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id_column',
        'name',
        'type',
        [
            'class' => 'yii\grid\ActionColumn',
            'contentOptions'=>['class'=>'button-column'],
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model, $key) {
                    return Html::a('', ['/collection-column/update', 'id' => $model->id_column],['class' => 'glyphicon glyphicon-pencil']);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('', ['/collection-column/delete', 'id' => $model->id_column],['class' => 'glyphicon glyphicon-trash','data' => [
                    'confirm' => 'Вы уверены что хотите удалить этот элемент?',
                    'method' => 'post',
                    ],]);
                }
            ], 
        ],
    ],
    'tableOptions'=>[
        'emptyCell' => '',
        'class' => 'table table-striped ids-style valign-middle table-hover'
    ]
]); ?>
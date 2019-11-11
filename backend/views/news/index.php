<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
if (Yii::$app->user->can('admin.news')) {
    $this->params['button-block'][] = Html::a('Добавить новость', ['create','id_page'=>Yii::$app->request->get('id_page',0)], ['class' => 'btn btn-success']);
}
?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id_news',
            'title',
            'rub.lineValue:text:Рубрика',
            'date_publish:date',
            'date_unpublish:date',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['class'=>'button-column']
            ],
        ],
        'tableOptions'=>[
            'emptyCell '=>'',
            'class'=>'table table-striped ids-style valign-middle table-hover'
        ]
    ]); ?>

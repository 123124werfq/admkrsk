<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\OpendataSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
if (Yii::$app->user->can('admin.opendata')) {
    $this->params['button-block'][] = Html::a('Добавить набор', ['create'], ['class' => 'btn btn-success']);
}
?>
<div class="opendata-index">
    <div class="ibox">
        <div class="ibox-content">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    'id_opendata',
                    //'id_collection',
                    'identifier',
                    'title',
                    //'description:ntext',
                    //'owner',
                    //'keywords',
                    //'id_user',
                    //'columns',
                    //'period',
                    //'created_at',
                    //'created_by',
                    //'updated_at',
                    //'updated_by',
                    //'deleted_at',
                    //'deleted_by',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' => ['class' => 'button-column'],
                    ],
                ],
                'tableOptions'=>[
                    'emptyCell' => '',
                    'class' => 'table table-striped ids-style valign-middle table-hover'
                ]
            ]); ?>

        </div>
    </div>
</div>

<?php

use common\models\Statistic;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\StatisticSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="statistic-index">
    <div class="ibox">
        <div class="ibox-content">
            <?= $this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="ibox">
        <div class="ibox-content">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'model',
                        'value' => function (Statistic $model) {
                            $model = $model->model ? new $model->model : null;
                            return $model ? $model::VERBOSE_NAME : null;
                        },
                    ],
                    [
                        'attribute' => 'model_id',
                        'value' => function (Statistic $model) {
                            $model = ($model->model && $model->model_id) ? $model->model::findOne($model->model_id) : null;
                            return $model ? $model->pageTitle : null;
                        },
                    ],
                    [
                        'attribute' => 'year',
                        'value' => function (Statistic $model) {
                            return $model->year ?: 'Всего';
                        },
                    ],
                    'views',
                    //'created_at',
                    'updated_at:datetime',
                ],
                'tableOptions' => [
                    'emptyCell' => '',
                    'class' => 'table table-striped ids-style valign-middle table-hover'
                ]
            ]); ?>

        </div>
    </div>
</div>

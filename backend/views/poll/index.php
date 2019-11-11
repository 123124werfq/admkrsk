<?php

use common\models\Poll;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PollSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
if (Yii::$app->user->can('admin.poll')) {
    $this->params['button-block'][] = Html::a('Добавить опрос', ['create'], ['class' => 'btn btn-success']);
}
?>
<div class="poll-index">
    <div class="ibox">
        <div class="ibox-content">
            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'title',
                    [
                        'attribute' => 'status',
                        'value' => function (Poll $model) {
                            return $model->statusName;
                        },
                    ],
                    //'description:ntext',
                    //'result:ntext',
                    //'is_anonymous:boolean',
                    //'is_hidden:boolean',
                    'date_start:datetime',
                    'date_end:datetime',
                    //'created_at:datetime',
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

        </div>
    </div>
</div>

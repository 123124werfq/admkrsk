<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $searchModel backend\models\search\ActionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-index">
    <div class="ibox">
        <div class="ibox-content">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'summary',
                        'label' => 'Действие',
                        'format' => 'raw',
                    ],
                    //'ip:ip',
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'enableSorting' => false,
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>

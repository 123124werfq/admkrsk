<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ActionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->labelPlural;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="action-index">
    <div class="ibox">
        <div class="ibox-content">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'summary',
                        'label' => 'Действие',
                        'format' => 'raw',
                    ],
                    //'ip:ip',
                    'created_at:datetime',
                ],
            ]); ?>

        </div>
    </div>
</div>

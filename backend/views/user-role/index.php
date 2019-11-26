<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserRoleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-role-index">
    <div class="ibox">
        <div class="ibox-content">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    //'name',
                    //'type',
                    'description:ntext:Название',
                    //'rule_name',
                    //'data',
                    //'created_at',
                    //'updated_at',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{view}',
                        'contentOptions'=>['class'=>'button-column'],
                    ],
                ],
                'tableOptions' => [
                    'emptyCell' => '',
                    'class' => 'table table-striped ids-style valign-middle table-hover'
                ],
            ]); ?>

        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;
use \common\models\Box;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CollectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$archive = Yii::$app->request->get('archive');

$this->title = $collection->name;
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="tabs-container">
    <?=$this->render('_nav',['model'=>$collection])?>

    <div class="tab-content">
      <div class="tab-pane active">
        <div class="panel-body">
          <div class="table-responsive">
            <div class="ibox">
                <div class="ibox-content">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'columns' => [
                            'id_page' => 'id_page',
                            'title' => 'title',
                            'alias' => [
                                'attribute' => 'alias',
                                'format' => 'html',
                                'value' => function ($model) {
                                    /**@var Page $model */
                                    return '<a class="break-all" target="_blank" href="' . $model->getUrl(true) . '">' . $model->getUrl() . '</a>';
                                },
                            ],
                            'created_at' => [
                                'attribute' => 'created_at',
                                'format' => [
                                    'date',
                                    'php:Y-m-d'
                                ],
                                'filterInputOptions' => [
                                    'class' => 'datepicker form-control',
                                ],
                            ],
                            'updated_at' => [
                                'attribute' => 'updated_at',
                                'format' => [
                                    'date',
                                    'php:Y-m-d'
                                ],
                                'filterInputOptions' => [
                                    'class' => 'datepicker form-control',
                                ],
                            ],
                            'viewsYear:prop' => [
                                'attribute' => 'viewsYear',
                                'label' => 'За год',
                                'format' => 'integer',

                            ],
                            'views:prop' => [
                                'attribute' => 'views',
                                'label' => 'Всего',
                                'format' => 'integer',

                            ],
                        ],
                        'tableOptions'=>[
                            'emptyCell '=>'',
                            'class'=>'table table-striped ids-style valign-middle table-hover'
                        ]
                    ]); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Collectionrecord */

$this->title = 'История изменений '.$model->id_record;
$this->params['breadcrumbs'][] = ['label' => 'Списки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->collection->name, 'url' => ['collection-record/index','id'=>$model->id_collection]];
$this->params['breadcrumbs'][] = ['label' => $model->id_record, 'url' => ['collection-record/view','id'=>$model->id_record]];
?>
<div class="ibox">
    <div class="ibox-content">

        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                'id',
                [
                    'attribute' => 'created_at',
                    'label' => 'Дата изменения',
                    'format' => 'datetime',
                ],
                [
                    'attribute' => 'created_by',
                    'label' => 'Изменил',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->user ? Html::a($model->user->username, ['/user/view', 'id' => $model->user->id]) : null;
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function($url, $model, $key) {
                            $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-eye-open"]);
                            return Html::a($icon, 'history-view?id='.$model->id, [
                                'data-pjax' => '0',
                            ]);
                        },
                    ],
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
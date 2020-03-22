<?php

use backend\assets\GridAsset;
use backend\controllers\ReserveController;
use common\models\GridSetting;
use common\models\HrContest;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$archive = Yii::$app->request->get('archive');

$this->title = 'Голосования';
$this->params['breadcrumbs'][] = $this->title;
GridAsset::register($this);

?>

<div class="service-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id' => 'id:integer:ID',
            'name' => 'name',
            'state' => [
                'label' => 'Статус',
                'format' => 'html',
                'value' => function ($model) {
                    return $model['state'];
                },
            ],
            'count' => [
                'label' => 'Кол-во заявок',
                'format' => 'html',
                'value' => function ($model) {
                    return $model['count'];
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{dynamic} {edit}',
                'buttons' => [
                    'edit' => function ($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                        return Html::a($icon, '/contest/edit?id='.$model['id'], [
                            'title' => 'Редактировать',
                            'aria-label' => 'Редактировать',
                            'data-pjax' => '0',
                        ]);
                    },
                    'dynamic' => function ($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-list-alt"]);
                        return Html::a($icon, '/contest/dynamic?id='.$model['id'], [
                            'title' => 'Результаты',
                            'aria-label' => 'Результаты',
                            'data-pjax' => '0',
                        ]);
                    },
                ],
                'contentOptions' => ['class' => 'button-column'],
            ],
        ],
        'tableOptions' => [
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover',
            'data-grid' => ReserveController::gridContest,
            'id' => 'grid',
        ]
    ]); ?>


</div>
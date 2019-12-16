<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$archive = Yii::$app->request->get('archive');

$this->title = 'Конкурсы';
$this->params['breadcrumbs'][] = $this->title;

/*
if (Yii::$app->user->can('admin.service')) {
    if ($archive)
        $this->params['action-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    else
        $this->params['action-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);

    $this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
}
*/
?>

<div class="service-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id_contest:integer:ID',
            'title',
            [
                'label' => 'Начало',
                'value' => function($model){
                    return date("d-m-Y H:i", $model->begin);
                }
            ],
            [
                'label' => 'Окончание',
                'value' => function($model){
                    return date("d-m-Y H:i", $model->end);
                }
            ],
            [
                'label' => 'Претенденты',
                'format' => 'html',
                'value' => function($model){
                    $pretenders = [];
                    foreach ($model->profiles as $profile) {
                        $pretenders[] = $profile->name;
                    }
                    return implode('<br>', $pretenders);
                }
            ],
            [
                'label' => 'Должности',
                'format' => 'html',
                'value' => function($model){
                    $positions = [];
                    foreach ($model->profiles as $profile) {
                        foreach ($profile->positions as $position)
                            $positions[] = $position->positionName;
                    }
                    $positions = array_unique($positions);
                    return implode('<br>', $positions);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{edit} {stop}',
                'buttons' => [
                    'stop' => function($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-stop"]);
                        return Html::a($icon, $url, [
                            'title' => 'Остановить',
                            'aria-label' => 'Остановить',
                            'data-pjax' => '0',
                        ]);
                    },
                    'edit' => function($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                        return Html::a($icon, $url, [
                            'title' => 'Редактировать',
                            'aria-label' => 'Редактировать',
                            'data-pjax' => '0',
                        ]);
                    },

                ],
                'contentOptions'=>['class'=>'button-column'],
            ],
        ],
        'tableOptions'=>[
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover'
        ]
    ]); ?>


</div>
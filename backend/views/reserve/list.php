<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$archive = Yii::$app->request->get('archive');

$this->title = 'Кадровый резерв';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="service-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id_profile:integer:ID',
            [
                'label'=> 'Резервист',
                'format' => 'html',
                'value' => function($model){

                    $username = $model->profile->recordData['surname'] . " " . $model->profile->recordData['name'] . " " . $model->profile->recordData['parental_name'];

                    return "<a href='/user/view?id={$model->profile->id_user}'>$username</a>";

                },
            ],
            [
                'label'=> 'Целевая должность',
                'format' => 'html',
                'value' => function($model){
                    return $model->positionName;
                },
            ],
            [
                'label'=> 'Дата голосования',
                'format' => 'html',
                'value' => function($model){

                    return date("d-m-Y", $model->contest_date);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{unreserve}',
                'buttons' => [
                    'unreserve' => function($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-ban-circle"]);
                        return Html::a($icon, $url, [
                            'title' => 'Убрать из резерва',
                            'aria-label' => 'Убрать из резерва',
                            'data-pjax' => '0',
                        ]);
                    },
                ],
                'contentOptions'=>['class'=>'button-column']
            ]
        ],
        'tableOptions'=>[
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover'
        ]
    ]); ?>


</div>
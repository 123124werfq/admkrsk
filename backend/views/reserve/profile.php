<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$archive = Yii::$app->request->get('archive');



$this->title = 'Анкеты кандидатов в кадровый резерв';
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
            'id_profile:integer:ID',
            [
                'label'=> 'Пользователь',
                'format' => 'html',
                'value' => function($model){

                    $username = $model->recordData['surname'] . " " . $model->recordData['name'] . " " . $model->recordData['parental_name'];

                    return "<a href='/user/view?id={$model->id_user}'>$username</a>";

                },
            ],
            [
                'label'=> 'Дата обновления информации',
                'value' => function($model){
                        return date("d-m-Y H:i", $model->updated_at?$model->updated_at:$model->created_at);
                },
            ],
            [
                'label'=> 'Статус',
                'format' => 'html',
                'value' => function($model){
                    return $model->getStatename(true);
                },
            ],
            [
                'label'=> 'Целевые должности',
                'format' => 'html',
                'value' => function($model){

                    $output = [];

                    foreach ($model->positions as $pos)
                        $output[] = $pos->positionName;

                    sort($output);

                    return implode("<br>", $output);

                },
            ],
            [
                'label'=> 'Дата включения в кадровый резерв',
                'value' => function($model){
                    return $model->reserve_date?date("d-m-Y H:i", $model->reserve_date):"не включен";
                },
            ],
            /*
            'old:boolean:Устарела',
            [
                'attribute'=>'online',
                'label'=>"Форма",
                'value' => function($model){
                    return ($model->online)?'Онлайн':'Оффлайн';
                },
            ],
            */
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {edtable} {ban} {archive} ',
                'buttons' => [
                    'archive' => function($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-floppy-disk"]);
                        return Html::a($icon, $url, [
                            'disabled' => true,
                            'title' => 'Восстановить',
                            'aria-label' => 'Восстановить',
                            'data-pjax' => '0',
                        ]);
                    },
                    'editable' => function($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                        return Html::a($icon, $url, [
                            'target' => '_blank',
                            'title' => 'Редактировать',
                            'aria-label' => 'Редактировать',
                            'data-pjax' => '0',
                        ]);
                    },
                    'ban' => function($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-ban-circle"]);
                        return Html::a($icon, $url, [
                            'title' => 'Заблокировать',
                            'aria-label' => 'Заблокировать',
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
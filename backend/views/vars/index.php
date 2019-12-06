<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$archive = Yii::$app->request->get('archive');

$this->title = 'Переменные';
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('admin.vars')) {
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
    }
    $this->params['button-block'][] = Html::a('Добавить переменную', ['create'],
        ['class' => 'btn btn-primary pull-right']);
}
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id_var',
        'name',
        'alias',
        'content:ntext',
        ['class' => 'yii\grid\ActionColumn',
         'template' => '<span class="btn btn-default">{update}</span> <span class="btn btn-default">{delete}</span>',
            'template' => '{view} {update} ' . ($archive ? '{undelete}' : '{delete}'),
            'buttons' => [
                'undelete' => function($url, $model, $key) {
                    $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-floppy-disk"]);
                    return Html::a($icon, $url, [
                        'title' => 'Восстановить',
                        'aria-label' => 'Восстановить',
                        'data-pjax' => '0',
                    ]);
                },
            ],
         'contentOptions'=>['class'=>'button-column']
        ]
    ],
    'tableOptions'=>[
        'class'=>'panel table table-striped ids-style valign-middle'
    ]
]); ?>
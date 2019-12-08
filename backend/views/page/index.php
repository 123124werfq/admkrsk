<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$archive = Yii::$app->request->get('archive');

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('admin.page')) {
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
    }
    $this->params['button-block'][] = Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success']);
}
$this->params['button-block'][] = Html::a('Дерево', ['tree'], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Экспорт XLS', ['','export'=>1], ['class' => 'btn btn-default']);
?>
<div class="page-index">
    <div class="ibox">
        <div class="ibox-content">
            <?=$this->render('_search', ['model' => $searchModel]); ?>
        </div>
    </div>
    <div class="ibox">
        <div class="ibox-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    'id_page',
                    [
                        'attribute' => 'title',
                        'format' => 'html',
                        'value' => function ($model) {
                            return $model->title.'<br><a target="_blank" href="'.$model->getUrl(true).'">'.$model->getUrl().'</a>';
                        },
                    ],
                    //'parent.title:text:Родитель',
                    'created_at:date',
                    'updated_at:date',
                    'viewsYear:integer:За год',
                    'views:integer:Всего',
                    [
                        'class' => 'yii\grid\ActionColumn',
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

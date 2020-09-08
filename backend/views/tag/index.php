<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\TagSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$archive = Yii::$app->request->get('archive');

$this->title = 'Теги';
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('admin.gallery'))
{
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
    }

    $this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
}

?>
<div class="collection-index">
    <div class="ibox">
        <div class="ibox-content">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    'name',
                    'frequency',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} ' . ($archive ? '{undelete}' : '{delete}'),
                        'buttons' => [
                            'undelete' => function ($url, $model, $key) {
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-floppy-disk"]);
                                return Html::a($icon, $url, [
                                    'title' => 'Восстановить',
                                    'aria-label' => 'Восстановить',
                                    'data-pjax' => '0',
                                ]);
                            },
                        ],
                        'contentOptions' => ['class' => 'button-column']
                    ],
                ],
                'tableOptions' => [
                    'emptyCell ' => '',
                    'class' => 'table table-striped ids-style valign-middle table-hover',
                    'id' => 'grid',
                ]
            ]); ?>
        </div>
    </div>
</div>

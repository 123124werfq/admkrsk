<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CollectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$archive = Yii::$app->request->get('archive');

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('admin.collection')) {
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
    }
    $this->params['button-block'][] = Html::a('Импортировать', ['import'], ['class' => 'btn btn-default']);
    $this->params['button-block'][] = Html::a('Добавить список', ['create'], ['class' => 'btn btn-success']);
}
?>

<div class="ibox">
    <div class="ibox-content">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
</div>
<div class="ibox">
    <div class="ibox-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                'id_collection',
                'name',
                'alias',
                [
                    'label'=>'Записей',
                    'value'=>function($model) {
                        return $model->getItems()->count();
                    }
                ],
                'created_at:date',
                'group.name',
                //'updated_at',
                //'updated_by',
                //'deleted_at',
                //'deleted_by',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions'=>['class'=>'button-column'],
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
                        'view' => function ($url, $model, $key) {
                            return Html::a('', ['/collection-record/index', 'id' => $model->id_collection],['class' => 'glyphicon glyphicon-eye-open']);
                        },
                    ],
                ],
            ],
            'tableOptions'=>[
                'emptyCell '=>'',
                'class'=>'table table-striped ids-style valign-middle table-hover'
            ]
        ]); ?>

    </div>
</div>
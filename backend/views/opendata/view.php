<?php

use common\models\CollectionColumn;
use common\models\OpendataData;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Opendata */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('История', ['history', 'id' => $model->id_opendata], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_opendata], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_opendata], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        'method' => 'post',
    ],
]);
?>
<div class="opendata-view">
    <div class="ibox">
        <div class="ibox-content">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id_opendata',
                    [
                        'attribute' => 'id_collection',
                        'value' => $model->collection->pageTitle,
                    ],
                    'identifier',
                    'title',
                    'description:ntext',
                    'owner',
                    'keywords',
                    [
                        'attribute' => 'id_page',
                        'value' => $model->page->title ?? null,
                    ],
                    [
                        'attribute' => 'id_user',
                        'value' => $model->user->username?? null,
                    ],
                    [
                        'attribute' => 'columns',
                        'value' => $model->columns ? implode(', ', ArrayHelper::map(CollectionColumn::findAll($model->columns), 'id_column', 'name')) : null,
                    ],
                    'period',
                ],
            ]) ?>

        </div>
    </div>

    <div class="row form-group">
        <div class="col-lg-9">
            <h3>Наборы открытых данных</h3>
        </div>
        <div class="col-lg-3 text-right">
            <?= Html::a('Загрузить файл', ['upload', 'id' => $model->primaryKey], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-content">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => new ActiveDataProvider([
                    'query' => $model->getData(),
                    'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
                ]),
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'structure.url:url:Структура',
                    'url:url:Структура',
                    'is_manual:boolean:Загружено вручную',
                    'created_at:datetime:Дата',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' => ['class' => 'button-column'],
                        'template' => '{delete-data}',
                        'buttons' => [
                            'delete-data' => function($url, $model, $key) {
                                /* @var OpendataData $model */
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                                return Html::a($icon, $url, [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'aria-label' => Yii::t('yii', 'Delete'),
                                    'data-pjax' => '0',
                                ]);
                            },
                        ],
                        'visibleButtons' => [
                            'delete-data' => function($model, $key, $index) {
                                /* @var OpendataData $model */
                                return (boolean) $model->is_manual;
                            },
                        ],
                    ],
                ],
                'tableOptions'=>[
                    'emptyCell' => '',
                    'class' => 'table table-striped ids-style valign-middle table-hover'
                ]
            ]); ?>

        </div>
    </div>
</div>

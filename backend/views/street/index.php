<?php

use common\models\City;
use common\models\District;
use common\models\Street;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\StreetSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$archive = Yii::$app->request->get('archive');

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('admin.address')) {
    if ($archive) {
        $this->params['button-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    } else {
        $this->params['button-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);
    }
    $this->params['button-block'][] = Html::a('Добавить улицу', ['create'], ['class' => 'btn btn-success']);
}
?>
<div class="street-index">
    <div class="ibox">
        <div class="ibox-content">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id_street',
                    [
                        'attribute' => 'id_city',
                        'filter' => Select2::widget([
                            'model' => $searchModel,
                            'attribute' => 'id_city',
                            'data' => $searchModel->id_city ? ArrayHelper::map([City::findOne($searchModel->id_city)], 'id_city', 'name') : [],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'ajax' => [
                                    'url' => '/city/list',
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                ],
                                'placeholder' => 'Выберите город',
                            ],
                        ]),
                        'value' => function (Street $model) {
                            return $model->city->name ?? null;
                        },
                    ],
                    [
                        'attribute' => 'id_district',
                        'label' => 'Район города',
                        'format' => 'raw',
                        'filter' => Select2::widget([
                            'model' => $searchModel,
                            'attribute' => 'id_district',
                            'data' => $searchModel->id_district ? ArrayHelper::map([District::findOne($searchModel->id_district)], 'id_district', 'name') : [],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'minimumInputLength' => 1,
                                'ajax' => [
                                    'url' => '/district/list',
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                                ],
                                'placeholder' => 'Выберите район города',
                            ],
                        ]),
                        'value' => function (Street $model) {
                            $districts = [];
                            foreach ($model->districts as $district) {
                                $districts[] = $district->name;
                            }
                            return $districts ? implode('<br>', $districts) : null;
                        },
                    ],
                    'name',
                    'is_active:boolean',
                    'is_updatable:boolean',

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
</div>

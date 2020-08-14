<?php

use common\models\House;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\House */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('История', ['history', 'id' => $model->id_house], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_house], ['class' => 'btn btn-primary']);

if ($model->isDeleted()) {
    $this->params['button-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->id_house], ['class' => 'btn btn-danger']);
} else {
    $this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_house], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
            'method' => 'post',
        ],
    ]);
}
?>
<div class="address-view">
    <div class="ibox">
        <div class="ibox-content">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id_house',
                    'postalcode',
                    'country.name',
                    'region.name',
                    'subregion.name',
                    'city.name',
                    'district.name',
                    'street.name',
                    'name',
                    'lat',
                    'lon',
                    [
                        'attribute' => 'fullname',
                        'value' => function (House $model) {
                            return $model->getFullName();
                        },
                    ],
                ],
            ]) ?>

        </div>
    </div>

    <div class="ibox">
        <div class="ibox-title">
            <div class="row">
                <div class="col-md-6">
                    <h2>Места</h2>
                </div>
                <div class="col-md-6">
                    <div class="button-block text-right">
                        <?= Html::a('Добавить место', ['create-place', 'id' => $model->id_house], ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="ibox-content">
            <?= GridView::widget([
                'dataProvider' => new ActiveDataProvider([
                    'query' => $model->getPlaces(),
                ]),
                'columns' => [
                    'id_place',
                    'name',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'buttons' => [
                            'view' => function($url, $model, $key) {
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-eye-open"]);
                                return Html::a($icon, ['view-place', 'id' => $model->id_place], [
                                    'title' => 'Просмотр',
                                    'aria-label' => 'Просмотр',
                                    'data-pjax' => '0',
                                ]);
                            },
                            'update' => function($url, $model, $key) {
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-pencil"]);
                                return Html::a($icon, ['update-place', 'id' => $model->id_place], [
                                    'title' => 'Редактировать',
                                    'aria-label' => 'Редактировать',
                                    'data-pjax' => '0',
                                ]);
                            },
                            'delete' => function($url, $model, $key) {
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                                return Html::a($icon, ['delete-place', 'id' => $model->id_place], [
                                    'title' => 'Удалить',
                                    'aria-label' => 'Удалить',
                                    'data' => [
                                        'pjax' => '0',
                                        'method' => 'post',
                                        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                    ],
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

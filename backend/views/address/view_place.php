<?php

use common\models\House;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Place */
/* @var $house common\models\House */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $house->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $house->pageTitle, 'url' => ['view', 'id' => $house->id_house]];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_place], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_place], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        'method' => 'post',
    ],
]);
?>
<div class="address-view">
    <div class="ibox">
        <div class="ibox-content">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id_place',
                    'house.fullname',
                    'name',
                    'lat',
                    'lon',
                ],
            ]) ?>

        </div>
    </div>
</div>

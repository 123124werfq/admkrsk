<?php

use backend\widgets\MapInputWidget;
use common\models\House;
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

            <?php echo MapInputWidget::widget(['name' => 'location', 'index' => $model->id_house, 'value' => [0,0]]) ?>

        </div>
    </div>
</div>

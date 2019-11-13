<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\HouseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-index">
    <div class="ibox">
        <div class="ibox-content">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'postalcode',
                    'region.name',
                    'subregion.name',
                    'city.name',
                    'district.name',
                    'street.name',
                    'name',
                    'fullname',
                ],
            ]); ?>

        </div>
    </div>
</div>

<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\HouseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="house-index">
    <div class="ibox">
        <div class="ibox-content">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    [
                        'attribute' => 'houseguid',
                        'label' => 'Фиас ID',
                    ],
                    [
                        'attribute' => 'housename',
                        'label' => 'Дом',
                    ],
                    [
                        'attribute' => 'fullname',
                        'label' => 'Полное название',
                    ],
                    //'buildnum',
                    //'enddate',
                    //'housenum',
                    //'ifnsfl',
                    //'ifnsul',
                    //'okato',
                    //'oktmo',
                    //'postalcode',
                    //'startdate',
                    //'strucnum',
                    //'terrifnsfl',
                    //'terrifnsul',
                    //'updatedate',
                    //'cadnum',
                    //'eststatus',
                    //'statstatus',
                    //'strstatus',
                    //'counter',
                    //'divtype',
                    //'aoguid',
                    //'houseguid',
                    //'houseid',
                    //'normdoc',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>
    </div>
</div>

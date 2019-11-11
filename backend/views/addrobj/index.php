<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\AddrObjSearch */
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
                    [
                        'attribute' => 'aoguid',
                        'label' => 'Фиас ID',
                    ],
                    [
                        'attribute' => 'addressname',
                        'label' => 'Адрес',
                    ],
                    [
                        'attribute' => 'fullname',
                        'label' => 'Полное название',
                    ],
                    //'areacode',
                    //'autocode',
                    //'citycode',
                    //'code',
                    //'enddate',
                    //'formalname',
                    //'ifnsfl',
                    //'ifnsul',
                    //'offname',
                    //'okato',
                    //'oktmo',
                    //'placecode',
                    //'plaincode',
                    //'postalcode',
                    //'regioncode',
                    //'shortname',
                    //'startdate',
                    //'streetcode',
                    //'terrifnsfl',
                    //'terrifnsul',
                    //'updatedate',
                    //'ctarcode',
                    //'extrcode',
                    //'sextcode',
                    //'plancode',
                    //'cadnum',
                    //'divtype',
                    //'actstatus',
                    //'aoguid',
                    //'aoid',
                    //'aolevel',
                    //'centstatus',
                    //'currstatus',
                    //'livestatus',
                    //'nextid',
                    //'normdoc',
                    //'operstatus',
                    //'parentguid',
                    //'previd',
                ],
            ]); ?>

        </div>
    </div>
</div>

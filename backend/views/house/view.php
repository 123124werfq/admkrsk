<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\FiasHouse */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="house-view">
    <div class="ibox">
        <div class="ibox-content">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'buildnum',
                    'enddate',
                    'housenum',
                    'ifnsfl',
                    'ifnsul',
                    'okato',
                    'oktmo',
                    'postalcode',
                    'startdate',
                    'strucnum',
                    'terrifnsfl',
                    'terrifnsul',
                    'updatedate',
                    'cadnum',
                    'eststatus',
                    'statstatus',
                    'strstatus',
                    'counter',
                    'divtype',
                    'aoguid',
                    'houseguid',
                    'houseid',
                    'normdoc',
                ],
            ]) ?>

        </div>
    </div>
</div>

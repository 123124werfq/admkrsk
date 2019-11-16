<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\House */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="address-view">
    <div class="ibox">
        <div class="ibox-content">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id_house',
                    'postalcode',
                    'region.name',
                    'city.name',
                    'district.name',
                    'street.name',
                    'name',
                    'fullname',
                ],
            ]) ?>

        </div>
    </div>
</div>

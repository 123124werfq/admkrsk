<?php

/* @var $this yii\web\View */
/* @var $model common\models\Place */
/* @var $house common\models\House */

$this->title = 'Редактировать место: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $house->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $house->pageTitle, 'url' => ['view', 'id' => $house->id_house]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="city-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form_place', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

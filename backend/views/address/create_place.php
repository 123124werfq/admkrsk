<?php

/* @var $this yii\web\View */
/* @var $model common\models\Place */
/* @var $house common\models\House */

$this->title = 'Создание места';
$this->params['breadcrumbs'][] = ['label' => $house->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $house->pageTitle, 'url' => ['view', 'id' => $house->id_house]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="city-create">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form_place', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

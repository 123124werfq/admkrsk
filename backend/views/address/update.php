<?php

/* @var $this yii\web\View */
/* @var $model common\models\House */

$this->title = 'Редактировать дом: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_house]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="city-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

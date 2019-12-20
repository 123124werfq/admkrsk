<?php

/* @var $this yii\web\View */
/* @var $model common\models\Region */

$this->title = 'Редактировать регион: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_region]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="region-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

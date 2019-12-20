<?php

/* @var $this yii\web\View */
/* @var $model common\models\City */

$this->title = 'Редактировать город: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_city]];
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

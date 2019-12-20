<?php

/* @var $this yii\web\View */
/* @var $model common\models\District */

$this->title = 'Редактировать район города: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_district]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="district-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

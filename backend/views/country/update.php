<?php

/* @var $this yii\web\View */
/* @var $model common\models\Country */

$this->title = 'Редактировать страну: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_country]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="country-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

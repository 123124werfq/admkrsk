<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Opendata */

$this->title = 'Редактировать набор: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_opendata]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="opendata-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

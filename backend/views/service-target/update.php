<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceTarget */

$this->title = 'Редактировать цель: ' . $model->name;
if (!empty($model->service))
	$this->params['breadcrumbs'][] = ['label' => $model->service->name, 'url' => ['service/view', 'id' => $model->id_service]];
?>
<div class="service-target-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

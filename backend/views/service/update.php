<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = 'Редактировать услугу: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Услуги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_service, 'url' => ['view', 'id' => $model->id_service]];
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
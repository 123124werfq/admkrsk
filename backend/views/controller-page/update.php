<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ControllerPage */

$this->title = 'Редактировать путь: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Пути', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
?>
<div class="controller-page-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Form */

$this->title = 'Редактировать форму: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Формы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_form]];
?>
<div class="form-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

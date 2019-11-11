<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FormInputType */

$this->title = 'Редактировать тип поля: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы полей', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_type]];
?>
<div class="form-input-type-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
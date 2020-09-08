<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Tag */

$this->title = 'Редактировать тег: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
?>
<div class="tag-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Media */

$this->title = 'Редактировать файл: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Файлы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_media]];
?>
<div class="media-update">    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GalleryGroup */

$this->title = 'Редактировать: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Группы галерей', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->gallery_group_id]];
?>
<div class="gallery-group-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

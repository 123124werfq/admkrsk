<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\GalleryGroup */

$this->title = 'Создать группу галерей';
$this->params['breadcrumbs'][] = ['label' => 'Группы галерей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-group-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

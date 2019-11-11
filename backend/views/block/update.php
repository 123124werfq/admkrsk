<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Block */

$this->title = 'Редактировать блок: ' . $model->id_block;
$this->params['breadcrumbs'][] = ['label' => $model->page->title, 'url' => ['page/layout','id' => $model->id_page]];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="block-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

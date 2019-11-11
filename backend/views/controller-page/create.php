<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ControllerPage */

$this->title = 'Добавить путь';
$this->params['breadcrumbs'][] = ['label' => 'Пути', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="controller-page-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

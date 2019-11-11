<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MenuLink */

$this->title = 'Добавить элемент меню';
$this->params['breadcrumbs'][] = ['label' => $model->menu->name, 'url' => ['menu/view','id'=>$model->id_menu]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
	<div class="col-md-8">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>

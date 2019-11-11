<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MenuLink */

$this->title = 'Редактировать элемент меню: ' . $model->id_link;
$this->params['breadcrumbs'][] = ['label' => $model->menu->name, 'url' => ['index', 'id' => $model->id_menu]];
?>
<div class="row">
	<div class="col-md-8">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>

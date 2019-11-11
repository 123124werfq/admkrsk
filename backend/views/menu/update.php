<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = 'Редактировать: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_menu]];
$this->params['breadcrumbs'][] = 'Редактировать';

$this->params['button-block'][] = Html::a('Содержимое', ['menu-link/index','id' => $model->id_menu], ['class' => 'btn btn-success']);
?>
<div class="row">
	<div class="col-md-8">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>
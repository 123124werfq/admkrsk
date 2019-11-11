<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Project */

$this->title = 'Создать проект или событие';
$this->params['breadcrumbs'][] = ['label' => 'Проекты и события', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-lg-9">
	    <?= $this->render('_form', [
	        'model' => $model,
	    ]) ?>
    </div>
</div>

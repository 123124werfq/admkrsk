<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceTarget */

$this->title = 'Добавить цель';
$this->params['breadcrumbs'][] = ['label' => 'услуги', 'url' => ['service/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
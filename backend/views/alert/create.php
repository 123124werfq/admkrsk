<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Alert */

$this->title = 'Добавить сообщение';
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
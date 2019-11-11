<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Vars */

$this->title = 'Добавить переменную';
$this->params['breadcrumbs'][] = ['label' => 'Переменные', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

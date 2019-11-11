<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Alert */

$this->title = 'Редактировать сообщение: ' . $model->id_alert;
$this->params['breadcrumbs'][] = ['label' => 'Сообщения', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_alert, 'url' => ['view', 'id' => $model->id_alert]];
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
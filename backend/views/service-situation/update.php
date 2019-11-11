<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceSituation */

$this->title = 'Редактировать жизненную ситуацию: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Жизненные ситуации', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_situation]];
?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

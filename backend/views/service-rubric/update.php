<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceRubric */

$this->title = 'Редактировать рубрику: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Рубрики', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_rub]];
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CollectionType */

$this->title = 'Редактировать: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы списков', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['collection-type-column/index', 'id' => $model->id_type]];
?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

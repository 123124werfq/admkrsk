<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CollectionColumn */

$this->title = 'Редактировать колонку: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->collection->name, 'url' => ['collection-column/index', 'id' => $model->id_collection]];
?>


<?= $this->render('_form', [
    'model' => $model,
]) ?>

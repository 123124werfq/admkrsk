<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CollectionColumn */

$this->title = 'Добавить составную колонку';
$this->params['breadcrumbs'][] = ['label' => $model->collection->name, 'url' => ['collection-column/index', 'id' => $model->id_collection]];

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="collection-column-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CollectionTypeColumn */

$this->title = 'Update Collection Type Column: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Collection Type Columns', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_column]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="collection-type-column-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CollectionTypeColumn */

$this->title = 'Create Collection Type Column';
$this->params['breadcrumbs'][] = ['label' => 'Collection Type Columns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-type-column-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

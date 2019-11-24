<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CollectionColumn */

$this->title = 'Create Collection Column';
$this->params['breadcrumbs'][] = ['label' => 'Collection Columns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-column-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

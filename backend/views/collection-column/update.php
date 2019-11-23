<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CollectionColumn */

$this->title = 'Update Collection Column: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Collection Columns', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_column]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="collection-column-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CollectionType */

$this->title = 'Update Collection Type: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Collection Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_type]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="collection-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

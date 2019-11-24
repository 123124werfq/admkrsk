<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Collectionrecord */

$this->title = 'Update Collectionrecord: ' . $model->id_record;
$this->params['breadcrumbs'][] = ['label' => 'Collectionrecords', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_record, 'url' => ['view', 'id' => $model->id_record]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="collectionrecord-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

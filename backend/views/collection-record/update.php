<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Collectionrecord */

$this->title = 'Редактироваться запись: ' . $model->id_record;
$this->params['breadcrumbs'][] = ['label' => $model->id_record, 'url' => ['view', 'id' => $model->id_record]];
?>
<div class="collectionrecord-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'collection'=>$collection
    ]) ?>

</div>

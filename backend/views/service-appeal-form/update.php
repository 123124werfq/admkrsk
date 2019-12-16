<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppealForm */

$this->title = 'Update Service Appeal Form: ' . $model->id_appeal;
$this->params['breadcrumbs'][] = ['label' => 'Service Appeal Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_appeal, 'url' => ['view', 'id' => $model->id_appeal]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="service-appeal-form-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

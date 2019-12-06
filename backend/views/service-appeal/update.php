<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppeal */

$this->title = 'Update Service Appeal: ' . $model->id_appeal;
$this->params['breadcrumbs'][] = ['label' => 'Service Appeals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_appeal, 'url' => ['view', 'id' => $model->id_appeal]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="service-appeal-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

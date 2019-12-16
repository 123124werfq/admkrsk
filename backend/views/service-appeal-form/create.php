<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppealForm */

$this->title = 'Create Service Appeal Form';
$this->params['breadcrumbs'][] = ['label' => 'Service Appeal Forms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-appeal-form-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

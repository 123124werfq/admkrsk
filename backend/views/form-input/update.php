<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FormInput */

$this->title = 'Update Form Input: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Form Inputs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_input]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="form-input-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

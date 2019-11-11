<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FormElement */

$this->title = 'Create Form Element';
$this->params['breadcrumbs'][] = ['label' => 'Form Elements', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-element-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

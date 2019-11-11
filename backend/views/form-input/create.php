<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FormInput */

$this->title = 'Create Form Input';
$this->params['breadcrumbs'][] = ['label' => 'Form Inputs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-input-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

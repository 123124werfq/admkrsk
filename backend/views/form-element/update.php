<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FormElement */

$this->title = 'Update Form Element: ' . $model->id_element;
$this->params['breadcrumbs'][] = ['label' => 'Form Elements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_element, 'url' => ['view', 'id' => $model->id_element]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="form-element-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

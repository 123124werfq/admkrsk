<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FormInputType */

$this->title = 'Создать тип поля';
$this->params['breadcrumbs'][] = ['label' => 'Типы полей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-input-type-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

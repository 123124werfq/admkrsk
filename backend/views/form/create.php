<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Form */

$this->title = 'Добавить форму';
$this->params['breadcrumbs'][] = ['label' => 'Формы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="form-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

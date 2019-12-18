<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppealForm */

$this->title = 'Редактировать связь: ' . $model->id_appeal;
$this->params['breadcrumbs'][] = ['label' => 'Связи', 'url' => ['index']];
?>
<div class="service-appeal-form-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

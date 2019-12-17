<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppeal */

$this->title = 'Редактирвоать связь: ' . $model->id_appeal;
$this->params['breadcrumbs'][] = ['label' => 'Связи', 'url' => ['index']];
?>
<div class="service-appeal-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

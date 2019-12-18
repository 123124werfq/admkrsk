<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppealForm */

$this->title = 'Добавить связь';
$this->params['breadcrumbs'][] = ['label' => 'Связи обжалования', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-appeal-form-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
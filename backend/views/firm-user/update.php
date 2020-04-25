<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FirmUser */

$this->title = 'Update Firm User: ' . $model->id_record;
$this->params['breadcrumbs'][] = ['label' => 'Firm Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_record, 'url' => ['view', 'id_record' => $model->id_record, 'id_user' => $model->id_user]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="firm-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

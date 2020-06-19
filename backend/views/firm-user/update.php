<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FirmUser */

$this->title = 'Подтрвеждение прав на данные: ' . $model->id_record;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_record, 'url' => ['view', 'id_record' => $model->id_record, 'id_user' => $model->id_user]];
?>
<div class="firm-user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

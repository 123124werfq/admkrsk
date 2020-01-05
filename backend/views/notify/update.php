<?php

use common\models\Notify;

/* @var $this yii\web\View */
/* @var $model common\models\Notify */

$this->title = 'Изменение параметров уведомлений: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Notifies', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notify-update">

    <h1>Изменение уведомлений (<?= Notify::getNotifyNameByClass($model->class) ?>)</h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

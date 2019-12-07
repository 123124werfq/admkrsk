<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceTarget */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Service Targets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['action-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_target]);

if ($model->isDeleted()) {
    $this->params['action-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->id_target]);
} else {
    $this->params['action-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_target], [
        'data' => [
            'confirm' => 'Вы уверены что хотите удалить этот элемент?',
            'method' => 'post',
        ],
    ]);
}
?>
<div class="service-target-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_target',
            'id_service',
            'id_form',
            'name',
            'reestr_number',
            'state',
            'modified_at',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
        ],
    ]) ?>

</div>

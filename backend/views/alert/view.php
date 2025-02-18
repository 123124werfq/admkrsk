<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Alert */

$this->title = $model->id_alert;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_alert], ['class' => 'btn btn-primary']);

if ($model->isDeleted()) {
    $this->params['button-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->id_alert], ['class' => 'btn btn-danger']);
} else {
    $this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_alert], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
            'method' => 'post',
        ],
    ]);
}
?>
<div class="alert-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_alert',
            'id_page',
            'content',
            'date_begin',
            'date_end',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
        ],
    ]) ?>

</div>

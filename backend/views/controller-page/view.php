<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ControllerPage */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Controller Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);

if ($model->isDeleted()) {
    $this->params['button-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->id], ['class' => 'btn btn-danger']);
} else {
    $this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
            'method' => 'post',
        ],
    ]);
}
?>
<div class="controller-page-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_page',
            'controller',
            'actions:ntext',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
        ],
    ]) ?>

</div>

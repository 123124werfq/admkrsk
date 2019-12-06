<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Gallery */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Galleries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['action-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_gallery]);

if ($model->isDeleted()) {
    $this->params['action-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->id_gallery]);
} else {
    $this->params['action-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_gallery], [
        'data' => [
            'confirm' => 'Вы уверены что хотите удалить этот элемент?',
            'method' => 'post',
        ],
    ]);
}
?>
<div class="gallery-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_gallery',
            'id_page',
            'name',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
        ],
    ]) ?>

</div>

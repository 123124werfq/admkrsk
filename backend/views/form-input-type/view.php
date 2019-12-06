<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\FormInputType */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Form Input Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['action-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_type]);

if ($model->isDeleted()) {
    $this->params['action-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->id_type]);
} else {
    $this->params['action-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_type], [
        'data' => [
            'confirm' => 'Вы уверены что хотите удалить этот элемент?',
            'method' => 'post',
        ],
    ]);
}
?>
<div class="form-input-type-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_type], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_type], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_type',
            'id_collection',
            'name',
            'regexp',
            'options:ntext',
            'type',
            'esia',
            'values:ntext',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
        ],
    ]) ?>

</div>

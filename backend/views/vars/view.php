<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Vars */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Vars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_var], ['class' => 'btn btn-primary']);

if ($model->isDeleted()) {
    $this->params['button-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->id_var], ['class' => 'btn btn-danger']);
} else {
    $this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_var], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
            'method' => 'post',
        ],
    ]);
}
?>
<div class="vars-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_var',
            'name',
            'alias',
            'content:ntext',
        ],
    ]) ?>

</div>

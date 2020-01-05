<?php

use common\models\Notify;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Notify */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Уведомления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        'method' => 'post',
    ],
]);
?>
<div class="notify-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'message',
                'label' => 'Сообщение',
            ],
            [
                'attribute' => 'class',
                'label' => 'Тип уведомлений',
                'value' => function ($model) {
                    /**@var Notify $model */
                    return Notify::getNotifyNameByClass($model->class);
                }
            ],
        ],
    ]) ?>

</div>

<?php

use yii\grid\GridView;
use \common\models\Notify;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NotifySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Уведомления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notify-index">

    <h1>Уведомления</h1>

    <p>
        <!--        --><? //= Html::a('Create Notify', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'class',
                'label' => 'Тип уведомлений',
                'value' => function ($model) {
                    /**@var Notify $model */
                    return Notify::getNotifyNameByClass($model->class);
                }
            ],
            [
                'attribute' => 'message',
                'label' => 'Сообщение',
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['class' => 'button-column']
            ],
        ],
    ]); ?>


</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Услуги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_service], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_service], ['class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены что хотите удалить эту услугу?',
        'method' => 'post',
    ],
]);
?>

<div class="row">
    <div class="col-sm-7">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_service',
            'id_rub',
            'reestr_number',
            'fullname',
            'name',
            'keywords:ntext',
            'addresses:ntext',
            'result:ntext',
            'client_type',
            'client_category:ntext',
            'duration:ntext',
            'documents:ntext',
            'price:ntext',
            'appeal:ntext',
            'legal_grounds:ntext',
            'regulations:ntext',
            'regulations_link:ntext',
            'duration_order:ntext',
            'availability:ntext',
            'procedure_information:ntext',
            'max_duration_queue:ntext',
            'old',
            'online',
        ],
    ]) ?>
    </div>
    <div class="col-sm-5">
        </div>
</div>

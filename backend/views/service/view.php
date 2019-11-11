<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Service */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="service-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_service], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_service], [
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
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
        ],
    ]) ?>

</div>

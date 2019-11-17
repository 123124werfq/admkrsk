<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceTarget */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Service Targets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="service-target-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_target], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_target], [
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
            'id_target',
            'id_service',
            'id_form',
            'name',
            'reestr_number',
            'old',
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

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\FirmUser */

$this->title = $model->id_record;
$this->params['breadcrumbs'][] = ['label' => 'Firm Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="firm-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id_record' => $model->id_record, 'id_user' => $model->id_user], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id_record' => $model->id_record, 'id_user' => $model->id_user], [
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
            'id_record',
            'id_user',
            'state',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
        ],
    ]) ?>

</div>

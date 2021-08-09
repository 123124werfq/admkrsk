<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\GalleryGroup */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Группы галлерей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$this->params['action-block'][] = Html::a('Редактировать', ['update', 'id' => $model->gallery_group_id]);

if ($model->isDeleted()) {
    $this->params['action-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->gallery_group_id]);
} else {
    $this->params['action-block'][] = Html::a('Удалить', ['delete', 'id' => $model->gallery_group_id], [
        'data' => [
            'confirm' => 'Вы уверены что хотите удалить этот элемент?',
            'method' => 'post',
        ],
    ]);
}

?>
<div class="gallery-group-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
        ],
    ]) ?>

</div>

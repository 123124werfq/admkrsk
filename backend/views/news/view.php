<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['action-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_news]);

if ($model->isDeleted()) {
    $this->params['action-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->id_news]);
}
else
{
    $this->params['action-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_news], [
        'data' => [
            'confirm' => 'Вы уверены что хотите удалить этот элемент?',
            'method' => 'post',
        ],
    ]);
}
?>
<div class="news-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_news',
            'id_page',
            'id_category',
            'id_rub',
            'id_media',
            'title',
            'description',
            'content:ntext',
            'date_publish',
            'date_unpublish',
            'state',
            'main',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
            'deleted_at',
            'deleted_by',
        ],
    ]) ?>

</div>

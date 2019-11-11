<?php

use common\models\CollectionColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Opendata */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('История', ['history', 'id' => $model->id_opendata], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_opendata], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_opendata], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        'method' => 'post',
    ],
]);
?>
<div class="opendata-view">
    <div class="ibox">
        <div class="ibox-content">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id_opendata',
                    [
                        'attribute' => 'id_collection',
                        'value' => $model->collection->pageTitle,
                    ],
                    'identifier',
                    'title',
                    'description:ntext',
                    'owner',
                    'keywords',
                    [
                        'attribute' => 'id_page',
                        'value' => $model->page->title ?? null,
                    ],
                    [
                        'attribute' => 'id_user',
                        'value' => $model->user->username,
                    ],
                    [
                        'attribute' => 'columns',
                        'value' => implode(', ', ArrayHelper::map(CollectionColumn::findAll($model->columns), 'id_column', 'name')),
                    ],
                    'period',
                ],
            ]) ?>

        </div>
    </div>
</div>

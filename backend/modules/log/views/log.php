<?php

use backend\modules\log\models\Log;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \backend\modules\log\models\Log */
/* @var $parent array */

$parentModel = !empty($parent) ? $model->entity->{$parent['relation']} : null;

$this->title = $model->primaryKey;
$this->params['breadcrumbs'][] = ['label' => $model->entity->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $parentModel ? $parentModel->pageTitle : $model->entity->pageTitle, 'url' => ['view', 'id' => $parentModel ? $parentModel->primaryKey : $model->entity->primaryKey]];
$this->params['breadcrumbs'][] = ['label' => 'История', 'url' => [$parentModel ? $parent['history'] : 'history', 'id' => $model->entity->primaryKey]];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('История', [$parentModel ? $parent['history'] : 'history', 'id' => $model->entity->primaryKey], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Восстановить', [$parentModel ? $parent['restore'] : 'restore', 'id' => $model->primaryKey], ['class' => 'btn btn-primary']);

$attributes = [];
foreach ($model->changeAttributes as $attribute => $value) {
    $attributes[] = [
        'attribute' => 'entity.' . $attribute,
        'label' => $model->getEntityAttributeLabel($attribute),
        'format' => 'raw',
        'value' => $model->diff($attribute),
    ];
}
?>
<div class="page-view">
    <div class="ibox">
        <div class="ibox-title">
            <h5>Лог</h5>
        </div>
        <div class="ibox-content">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'model',
                    'model_id',
                    [
                        'attribute' => 'created_at',
                        'label' => 'Дата изменения',
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'created_by',
                        'label' => 'Изменил',
                        'format' => 'raw',
                        'value' => function (Log $model) {
                            return $model->user ? Html::a($model->user->username, ['/user/view', 'id' => $model->user->id]) : null;
                        },
                    ],
                    [
                        'attribute' => 'data',
                        'label' => 'Изменено атрибутов',
                        'format' => 'raw',
                        'value' => function (Log $model) {
                            return $model->countChanges;
                        }
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-title">
            <h5>Изменения</h5>
        </div>
        <div class="ibox-content">
            <?php if ($attributes): ?>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => $attributes,
                ]) ?>
            <?php else: ?>
                Нет изменений
            <?php endif; ?>
        </div>
    </div>
</div>

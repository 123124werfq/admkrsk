<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Collection */

$this->title = 'Редактировать представление "'.$model->name.'"';
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->parent->name, 'url' => ['collection/view','id'=>$model->id_parent_collection]];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['collection/view','id'=>$model->id_collection]];
?>

<div class="collection-create">
    <div class="ibox">
        <div class="ibox-content">
            <?= $this->render('_form_view', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
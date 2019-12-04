<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Collection */

$this->title = 'Редактирование списка: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_collection]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="collection-update">

    <div class="ibox">
        <div class="ibox-content">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
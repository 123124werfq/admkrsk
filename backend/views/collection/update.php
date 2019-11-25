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
    <ul class="nav nav-tabs" role="tablist">
        <li class="active"><a class="nav-link" data-toggle="tab" href="#tab-1">Дочерние разделы</a></li>
        <li>
            <?=Html::a('Шаблон', ['layout', 'id' => $model->id_collection], ['class' => 'nav-link'])?>
        </li>
        <li>
            <?=Html::a('Новости', ['news/index', 'id_collection' => $model->id_collection], ['class' => 'nav-link'])?>
        </li>
    </ul>
    <div class="ibox">
        <div class="ibox-content">
            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>
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

    <h2><?=Html::a('Добавить', ['collection-column/create','id'=>$model->id_collection], ['class' => 'btn btn-primary pull-right'])?> Столбцы</h2>
    <br/>
    <div class="ibox">
        <div class="ibox-content">
            <?=$this->render('/collection-column/index', [
                	'id_collection' => $model->id_collection,
            ])?>
        </div>
    </div>
</div>

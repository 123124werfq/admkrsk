<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Page */

$this->render('/page/_head',['model'=>$model]);

$this->title = 'Редактировать раздел: ' . $model->pageTitle;
if (empty($model->partition))
    $this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
else 
    $this->params['breadcrumbs'][] = ['label' => $model->partition->title, 'url' => ['partition','id'=>$model->partition->id_page]];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_page]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="page-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

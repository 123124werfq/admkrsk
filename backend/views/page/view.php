<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Page */

$this->title = $model->pageTitle;

if (empty($model->partition))
    $this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
else
    $this->params['breadcrumbs'][] = ['label' => $model->partition->title, 'url' => ['partition','id'=>$model->partition->id_page]];

if (!empty($model->parent))
    $this->params['breadcrumbs'][] = ['label' => $model->parent->title, 'url' => ['view', 'id' => $model->id_parent]];

$this->params['breadcrumbs'][] = $this->title;

$this->render('_head',['model'=>$model]);
?>

<div class="row">
    <div class="col-lg-9">
        <?=$this->render('_view',[
            'model'=>$model,
            'submenu'=>$submenu
        ]);?>
    </div>
</div>
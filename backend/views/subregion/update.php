<?php

/* @var $this yii\web\View */
/* @var $model common\models\Subregion */

$this->title = 'Редактировать район: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_subregion]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="subregion-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

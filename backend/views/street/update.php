<?php

/* @var $this yii\web\View */
/* @var $model common\models\Street */

$this->title = 'Редактировать улицу: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_street]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="street-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

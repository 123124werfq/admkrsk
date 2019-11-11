<?php

/* @var $this yii\web\View */
/* @var $model common\models\Poll */

$this->title = 'Редактировать опрос: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_poll]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="poll-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

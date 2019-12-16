<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Application */

$this->title = 'Редактировать приложение: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_application]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="application-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

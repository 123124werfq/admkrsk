<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Institution */

$this->title = 'Редактировать организацию: ' . $model->shortname;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_institution]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="institution-update">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Application */

$this->title = 'Создание приложения';
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="application-create">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

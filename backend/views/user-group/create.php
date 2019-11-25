<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserGroup */

$this->title = 'Создание группы пользователей';
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-group-create">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

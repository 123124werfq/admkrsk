<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Collection */

$this->title = 'Создать представление из списка "'.$model->getParent()->one()->name.'"';
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-create">
    <div class="ibox">
        <div class="ibox-content">
            <?= $this->render('_form_view', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>
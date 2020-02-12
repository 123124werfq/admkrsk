<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CollectionType */

$this->title = 'Добавит тип списка';
$this->params['breadcrumbs'][] = ['label' => 'Типы списков', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ibox">
    <div class="ibox-content">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    </div>
</div>

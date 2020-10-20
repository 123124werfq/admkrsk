<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Collectionrecord */

$this->title = 'Добавить запись';
$this->params['breadcrumbs'][] = ['label' => $collection->name, 'url' => ['index','id'=>$collection->id_collection]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ibox">
    <div class="ibox-content">
        <?= $this->render('_form', [
            'model' => $model,
            'collection'=>$collection,
        ]) ?>
    </div>
</div>
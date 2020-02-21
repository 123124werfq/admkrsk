<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Collectionrecord */

$this->title = $model->id_record;
$this->params['breadcrumbs'][] = ['label' => 'Списки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->collection->name, 'url' => ['collection-record','id'=>$model->id_record]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="collectionrecord-view">
    <?=frontend\widgets\CollectionRecordWidget::widget([
        'collectionRecord'=>$model,
        //'renderTemplate'=>true,
    ]);?>
</div>

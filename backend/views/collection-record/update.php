<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Collectionrecord */

$this->title = 'Редактироваться запись: ' . $model->id_record;
$this->params['breadcrumbs'][] = ['label' => $model->collection->name, 'url' => ['index', 'id' => $model->id_collection]];
?>
<div class="ibox">
	<div class="ibox-content">
	    <?= $this->render('_form', [
	        'model' => $model,
	        'collection'=>$collection
	    ]) ?>
    </div>
</div>
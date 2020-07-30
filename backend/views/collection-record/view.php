<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Collectionrecord */

$this->title = $model->id_record;
$this->params['breadcrumbs'][] = ['label' => 'Списки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->collection->name, 'url' => ['collection-record/index','id'=>$model->id_collection]];
$this->params['breadcrumbs'][] = $this->title;

if (!empty($collection->form->template))
{
	$this->params['button-block'][] = Html::a('Скачать .doc', ['form/make-doc','id_record'=>$model->id_record,'id_collection'=>$collection->id_collection],
            ['class' => 'btn btn-primary']);
}

\yii\web\YiiAsset::register($this);
?>

<div class="ibox">
	<div class="ibox-content">
    <?=frontend\widgets\CollectionRecordWidget::widget([
        'collectionRecord'=>$model,
        //'renderTemplate'=>true,
        'columns'=>$collection->getColumns()->indexBy('alias')->all(),
    ]);?>
    </div>
</div>

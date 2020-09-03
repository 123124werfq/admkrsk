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

<?php
	/*$data = $model->getData();
	//var_dump($data);

	$array = [ 1598893200,
        1598979600,
        1599066000,
        1599498000,
        1599584400,
        1599670800,
        1600102800,
        1600189200,
        1600275600,
        1600707600,
        1600794000,
        1600880400,
        1601312400,
        1601398800,
        1601485200,
        1601917200,
        1602003600,
        1602090000];


	$begin = 1599152400;
	$end = 1599238800;

	echo "string";

	foreach ($array as $key => $value) {

		echo date('d.m.Y',$value).'<br>';

		if ($value>=$begin && $value<=$end)
			echo $value;
		# code...
	}*/
?>
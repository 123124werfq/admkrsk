<?php 
	use yii\helpers\Html;
	
	$this->params['button-block'][] = '<a href="'.$model->getUrl(true).'" class = "btn btn-default">Посмотреть</a>';
	$this->params['button-block'][] = Html::a('История', ['history', 'id' => $model->id_page], ['class' => 'btn btn-default']);
	$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_page], ['class' => 'btn btn-primary']);
	$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_page], [
	    'class' => 'btn btn-danger',
	    'data' => [
	        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
	        'method' => 'post',
	    ],
	]);
?>
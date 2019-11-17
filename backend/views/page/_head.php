<?php 
	use yii\helpers\Html;
	
	$this->params['button-block'][] = '<a href="'.$model->getUrl(true).'" class = "btn btn-default">Посмотреть</a>';
	$this->params['action-block'][] = Html::a('История', ['page/history', 'id' => $model->id_page]);
	$this->params['action-block'][] = Html::a('Редактировать', ['page/update', 'id' => $model->id_page]);
	$this->params['action-block'][] = Html::a('Удалить', ['page/delete', 'id' => $model->id_page], [
	    'data' => [
	        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
	        'method' => 'post',
	    ],
	]);
?>
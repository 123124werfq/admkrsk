<?php
	use yii\helpers\Html;

	$this->params['button-block'][] = '<a href="'.$model->getUrl(true).'" class = "btn btn-default">Посмотреть</a>';
	$this->params['action-block'][] = Html::a('Редактировать', ['page/update', 'id' => $model->id_page]);
    $this->params['action-block'][] = Html::a('Копировать', ['page/copy', 'id' => $model->id_page]);
	$this->params['action-block'][] = Html::a('История', ['page/history', 'id' => $model->id_page]);

    if ($model->isDeleted()) {
        $this->params['action-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->id_page]);
    } else {
        $this->params['action-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_page], [
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]);
    }
?>
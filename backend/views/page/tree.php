<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Дерево разделов';
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('admin.page'))
    $this->params['button-block'][] = Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success']);
?>

<?php foreach ($tree[0] as $key => $data) {
	echo $this->render('_tree',['data'=>$data,'tree'=>$tree,'offset'=>0]);
}
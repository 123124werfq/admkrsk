<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Разделы';
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('admin.page'))
    $this->params['button-block'][] = Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success']);

//$this->params['button-block'][] = Html::a('Дерево', ['tree'], ['class' => 'btn btn-default']);
?>

<div class="partitions">
	<?php foreach ($partitions as $key => $partition){?>
		<a href="/page/partition?id=<?=$partition->id_page?>" class="partition">
			<?=$partition->title?>
		</a>
	<?php }?>
</div>
<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $model->title;

if (Yii::$app->user->can('admin.page'))
{
	$modelPartition = $model->parents()->andWhere('is_partition = TRUE')->one();
	
	if (!empty($modelPartition))
    	$this->params['breadcrumbs'][] = ['label' => $modelPartition->title, 'url' => ['partition', 'id' => $modelPartition->id_page]];

    //$this->params['button-block'][] = Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success']);
}

$this->params['breadcrumbs'][] = $this->title;

$this->render('/page/_head',['model'=>$model]);
?>

<?php if (!empty($partitions)){?>
<h2>Подразделы</h2>

<div class="partitions">
	<?php foreach ($partitions as $key => $partition){?>
		<a href="/page/partition?id=<?=$partition->id_page?>" class="partition">
			<?=$partition->title?>
		</a>
	<?php }?>
</div>
<hr>
<?php }?>

<div class="partitions">
	<a href="/page?id_partition=<?=$model->id_page?>" class="partition">
		Страницы
	</a>
	<a href="/news?id_page=<?=$model->id_page?>" class="partition">
		Новости
	</a>
	<a href="/collection?id_page=<?=$model->id_page?>" class="partition">
		Списки
	</a>
	<a href="/form?id_page=<?=$model->id_page?>" class="partition">
		Формы
	</a>
</div>
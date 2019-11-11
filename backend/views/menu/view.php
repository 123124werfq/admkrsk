<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Menu */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
	if ($model->type == $model::TYPE_LIST)
	{
		echo $this->render('_list',['model'=>$model]);
	} elseif ($model->type == $model::TYPE_TABS){
		echo $this->render('_tabs',['model'=>$model]);
	} elseif ($model->type == $model::TYPE_LEVELS){
		echo $this->render('_levels',['model'=>$model]);
	}
 }?>

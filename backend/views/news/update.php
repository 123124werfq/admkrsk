<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = 'Редактировать новость: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => $page->title, 'url' => ['index','id_page'=>$page->id_page]];
$this->params['breadcrumbs'][] = ['label' => $model->id_news, 'url' => ['view', 'id' => $model->id_news]];

$this->params['button-block'][] = '<a href="'.Yii::$app->params['frontendUrl'].$model->getUrl().'" class = "btn btn-default">Посмотреть</a>'
?>
<div class="news-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

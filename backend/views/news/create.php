<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label' => $page->title, 'url' => ['index','id_page'=>$page->id_page]];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>

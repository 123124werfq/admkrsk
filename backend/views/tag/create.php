<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Tag */

$this->title = 'Добавить тег';
$this->params['breadcrumbs'][] = ['label' => 'Теги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tag-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

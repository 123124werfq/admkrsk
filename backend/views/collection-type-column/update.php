<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CollectionTypeColumn */

$this->title = 'Редактировать: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы списков', 'url' => ['index','id'=>$model->id_type]];
?>
<div class="collection-type-column-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

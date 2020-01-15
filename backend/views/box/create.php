<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Box */

$this->title = 'Добавить группу';
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

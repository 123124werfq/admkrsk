<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceSituation */

$this->title = 'Добавить жизненную ситуацию';
$this->params['breadcrumbs'][] = ['label' => 'Жизненные ситуации', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-situation-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

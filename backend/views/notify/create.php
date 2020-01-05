<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Notify */

$this->title = 'Создание уведомлений';
$this->params['breadcrumbs'][] = ['label' => 'Notifies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notify-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

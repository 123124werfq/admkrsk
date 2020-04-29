<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FirmUser */

$this->title = 'Create Firm User';
$this->params['breadcrumbs'][] = ['label' => 'Firm Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firm-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

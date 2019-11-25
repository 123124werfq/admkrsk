<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\UserGroup */

$this->title = 'Update User Group: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'User Groups', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_user_group]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-group-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

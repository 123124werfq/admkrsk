<?php


use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Opendata */

$this->title = 'Создание набора';
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="opendata-create">
    <div class="ibox">
        <div class="ibox-content">

            <?= $this->render('_form', [
                'model' => $model,
            ]) ?>

        </div>
    </div>
</div>

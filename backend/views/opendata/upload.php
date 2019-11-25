<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Opendata */
/* @var $uploadForm backend\models\forms\OpendataUploadForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('История', ['history', 'id' => $model->id_opendata], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_opendata], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_opendata], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        'method' => 'post',
    ],
]);
?>

<div class="opendata-update">
    <div class="ibox">
        <div class="ibox-content">

            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <?= $form->errorSummary($uploadForm) ?>

            <?= $form->field($uploadForm, 'structure')->fileInput() ?>

            <?= $form->field($uploadForm, 'data')->fileInput() ?>

            <hr>

            <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

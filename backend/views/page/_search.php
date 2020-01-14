<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\search\PageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-search search-form">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'fieldConfig'=>[
            'template' => '{input}',
        ]
    ]); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'title')->textInput(['placeholder'=>'Заголовок']) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'alias')->textInput(['placeholder'=>'URL']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Сбросить',['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php // echo $form->field($model, 'active') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    <?php ActiveForm::end(); ?>

</div>

<div style="margin-top: 10px">
    <a style="color: white" href="<?= Url::to(['', 'pageSize' => 10]) ?>"><button class="btn btn-primary">10</button></a>
    <a style="color: white" href="<?= Url::to(['', 'pageSize' => 20]) ?>"><button class="btn btn-primary">20</button></a>
    <a style="color: white" href="<?= Url::to(['', 'pageSize' => 40]) ?>"><button class="btn btn-primary">40</button></a>
</div>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\FormSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'fieldConfig'=>[
            'template' => '{input}',
        ]
    ]); ?>

    <?= ''//$form->field($model, 'id_collection') ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'name')->textInput(['placeholder'=>"Название"]) ?>
        </div>
        <div class="col-sm-3">
            <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('Сброс', ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'deleted_at') ?>

    <?php // echo $form->field($model, 'deleted_by') ?>

    

    <?php ActiveForm::end(); ?>

        <div class="ibox">
            <div class="ibox">
                <a style="color: white" href="<?= Url::to(['', 'pageSize' => 10]) ?>"><button class="btn btn-primary">10</button></a>
                <a style="color: white" href="<?= Url::to(['', 'pageSize' => 20]) ?>"><button class="btn btn-primary">20</button></a>
                <a style="color: white" href="<?= Url::to(['', 'pageSize' => 40]) ?>"><button class="btn btn-primary">40</button></a>
            </div>
        </div>
    </div>
</div>

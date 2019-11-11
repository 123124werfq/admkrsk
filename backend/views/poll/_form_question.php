<?php

use common\models\Question;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Question */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'type')->dropDownList(Question::getTypeNames()) ?>

<?= $form->field($model, 'question')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6, 'class'=>'redactor']) ?>

<?= $form->field($model, 'ord')->textInput() ?>

<?= $form->field($model, 'is_option')->checkbox() ?>

<?= $form->field($model, 'is_hidden')->checkbox() ?>

<?= $form->field($model, 'chart_type')->dropDownList(Question::getChartTypeNames()) ?>

<h2>Варианты ответов</h2>

<div id="poll-questions" class="multiinput sortable">
    <?php foreach ($model->getRecords('answers') as $key => $data) {?>
        <div class="row">
            <div class="col-md-12">
                <?= Html::hiddenInput("Answer[answers][$key][id_poll_answer]", $data->id_poll_answer, ['id' => 'Answer_id_poll_answer_' . $key]); ?>
                <?= Html::hiddenInput("Answer[answers][$key][ord]", $data->ord, ['id' => 'Answer_ord_' . $key]); ?>
                <div class="form-group">
                    <?= Html::label('Ответ', 'Answer_name_' . $key) ?>
                    <?= Html::textInput("Answer[answers][$key][answer]", $data->answer, ['class'=>'form-control', 'id' => 'Answer_answer_' . $key]); ?>
                </div>
                <div class="form-group">
                    <?= Html::label('Описание', 'Answer_name_' . $key) ?>
                    <?= Html::textInput("Answer[answers][$key][description]", $data->description, ['class'=>'form-control redactor', 'id' => 'Answer_description_' . $key]); ?>
                </div>
            </div>
        </div>
    <?php }?>
</div>

<a onclick="return addInput('poll-questions')" href="#" class="btn btn-default">Добавить еще</a>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

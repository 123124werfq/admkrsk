<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Opendata */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs("$('#opendata-id_collection').change(function () {
    var id_collection = $(this).val();
    
    if (id_collection) {
        $.ajax({
            url: '" . Url::to(['/collection/columns']) . "',
            data: { id: id_collection },
            success: function(data) {
                if (data.results) {
                    $.each(data.results, function(id, name) {
                        $('#opendata-columns').append('<option value=\"' + id + '\" selected=\"selected\">' + name + '</option>');
                    });
                }
            }
        });
    }
})");
?>

<?php $form = ActiveForm::begin(); ?>
<?= $form->errorSummary($model) ?>
<?= $form->field($model, 'identifier')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'owner')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'urls')->widget(Select2::class, [
    'pluginOptions' => [
        'allowClear' => true,
        'tags' => true,
        'multiple' => true,
        'placeholder' => 'Начните ввод',
    ],
]) ?>

<?= $form->field($model, 'id_user')->widget(Select2::class, [
    'data' => $model->id_user ? ArrayHelper::map([$model->user], 'id', 'username') : [],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 1,
        'placeholder' => 'Начните ввод',
        'ajax' => [
            'url' => Url::toRoute(['/user/list']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
    ],
]) ?>

<?= $form->field($model, 'id_collection')->widget(Select2::class, [
    'data' => $model->id_collection ? ArrayHelper::map([$model->collection], 'id_collection', 'name') : [],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 1,
        'placeholder' => 'Начните ввод',
        'ajax' => [
            'url' => Url::toRoute(['/collection/list']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
    ],
]) ?>

<?= $form->field($model, 'columns')->dropDownList($model->id_collection ? ArrayHelper::map($model->collection->columns, 'id_column', 'name') : [], ['multiple' => true]) ?>

<?= $form->field($model, 'period')->textInput() ?>

<?php if (Yii::$app->user->can('admin.opendata')): ?>

    <hr>

    <h3>Доступ</h3>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

    <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

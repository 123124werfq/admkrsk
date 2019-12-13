<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\CollectionColumn;
use common\models\Collection;
/* @var $this yii\web\View */
/* @var $model common\models\Collection */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_group')->dropDownList(Collection::getArrayByAlias('collection_group'))?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?php if (Yii::$app->user->can('admin.collection')): ?>

    <hr>
    <?= $form->field($model, 'template')->textInput(['class' => 'form-control redactor'])?>

    <?= $form->field($model, 'template_element')->textInput(['class' => 'form-control redactor'])?>

    <table class="table">
    <?php foreach ($model->columns as $key => $column) {
        echo '<tr><th width="100">'.($column->alias?$column->alias:'column_'.$column->id_column).'</th><td>'.$column->name.'</td></tr>';
    }?>
    </table>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?php if (!$model->isNewRecord){?>
    <?=$form->field($model, 'label')->widget(Select2::class, [
        'data' => ArrayHelper::map(CollectionColumn::find()->where([
            'id_collection'=>$model->id_collection,
            'type'=>[CollectionColumn::TYPE_INPUT,CollectionColumn::TYPE_INTEGER]
        ])->all(),'id_column','name'),
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
            'placeholder' => 'Выберите колонки',
        ],
        'options'=>['multiple' => true,]
    ])->hint('Выберите колонки из которых будет составляться представление для отображения в списках')?>
    <?php }?>

    <hr>

    <h3>Доступ</h3>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

    <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

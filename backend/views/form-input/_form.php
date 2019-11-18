<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use common\models\FormInputType;
use common\models\FormInput;

use common\models\Collection;
use common\models\CollectionColumn;

$types = FormInputType::find()->all();

$select_ids = [];
foreach ($types as $key => $type) {
    if ($type->type == CollectionColumn::TYPE_SELECT || $type->type == CollectionColumn::TYPE_RADIO)
        $select_ids[] = $type->id_type;
}
/* @var $this yii\web\View */
/* @var $model common\models\FormInput */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-input-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'formInput-form'
        ]
    ]); ?>

    <?= $form->field($model, 'required')->checkBox()?>

    <?=$form->field($model, 'id_type')->widget(Select2::class, [
        'data' => ArrayHelper::map(FormInputType::find()->all(), 'id_type', 'name'),
        'pluginOptions' => [
            'allowClear' => true,
            'placeholder' => 'Выберите тип поля',
        ],
    ])?>

    <?=$form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?=$form->field($model, 'label')->textInput(['maxlength' => 255]) ?>

    <?=$form->field($model, 'hint')->textInput(['maxlength' => 255]) ?>

    <hr>
    <div class="row">
        <div class="col-sm-6">
            <?=$form->field($model, 'visibleInput')->dropDownLIst(
                ArrayHelper::map(FormInput::find()->where(['id_form'=>$model->id_form])->andWhere('id_input <> '.(int)$model->id_input)->all(), 'id_input', 'name'),['prompt'=>'Выберите поле зависимости']
            ) ?>
        </div>
        <div id="visibleInputValues" class="col-sm-6">
            <?=(!empty($model->visibleInput))?$this->render('_input',['visibleInput'=>$model->visibleInputModel,'model'=>$model,'form'=>$form]):''?>
        </div>
    </div>

    <div data-visible-field="forminput-id_type" data-values="<?=implode(',', $select_ids)?>">
        <?=$form->field($model, 'id_collection')->widget(Select2::class, [
            'data' => ArrayHelper::map(Collection::find()->all(), 'id_collection', 'name'),
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => 'Выберите коллекцию',
            ],
        ])?>

        <?=$form->field($model, 'values')->textarea(['rows' => 6])?>
    </div>

    <div id="input-options">
        <?php
            if (!empty($model->id_type))
                echo $this->render('_options',['type'=>$model->type]);
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

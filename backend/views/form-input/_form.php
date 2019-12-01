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

$visibleInputs = [];
if (!empty($model->id_form))
{
    $visibleInputs = ArrayHelper::map(FormInput::find()
                        ->where(['id_form'=>$model->id_form, 'type'=>[CollectionColumn::TYPE_SELECT,CollectionColumn::TYPE_CHECKBOX]])
                        ->andWhere('id_input <> '.(int)$model->id_input)->all(), 'id_input', 'name');
}
?>
<div class="form-input-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'formInput-form'
        ]
    ]); ?>

    <?= $form->field($model, 'required')->checkBox()?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'type')->dropDownList(CollectionColumn::getTypeLabel()) ?>
        </div>
        <div class="col-sm-6">
            <?=$form->field($model, 'id_type')->widget(Select2::class, [
                'data' => ArrayHelper::map(FormInputType::find()->where(['type'=>$model->type])->all(), 'id_type', 'name'),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => 'Настройки поля',
                ],
            ])?>
        </div>
    </div>

    <?=$form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?=$form->field($model, 'label')->textInput(['maxlength' => 255]) ?>

    <?=$form->field($model, 'hint')->textInput(['maxlength' => 255]) ?>

    <?=$form->field($model, 'fieldname')->textInput(['maxlength' => 255]) ?>

    <?php if ($model->type == CollectionColumn::TYPE_SELECT || $model->type == CollectionColumn::TYPE_RADIO){?>
        <?=$form->field($model, 'id_collection')->widget(Select2::class, [
            'data' => ArrayHelper::map(Collection::find()->all(), 'id_collection', 'name'),
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => 'Выберите коллекцию',
            ],
        ])?>
    <?php }?>

    <?php if ($model->type == CollectionColumn::TYPE_SELECT ||
              $model->type == CollectionColumn::TYPE_RADIO ||
              $model->type == CollectionColumn::TYPE_CHECKBOX ||
              $model->type == CollectionColumn::TYPE_JSON){?>
        <?=$form->field($model, 'values')->textarea(['rows' => 6])->hint('Вводить через ;')?>
    <?php }?>

    <div id="input-options">
        <?php
            echo $this->render('_options',['model'=>$model]);
        ?>
    </div>

    <br/>
    <h3>Настройка отображения</h3>
    <?php
        echo $this->render('_element_options',['element'=>$model->element]);
    ?>

    <?php if (!empty($visibleInputs)){
        $records = $model->getRecords('visibleInputs');
    ?>
            <p>Отображать если:</p>
            <div id="visibles" class="multiyiinput">
            <?php foreach ($records as $key => $visibleInput) {?>
                <div class="row">
                    <div class="col-sm-5">
                    <?=$form->field($visibleInput, "[visibleInputs][$key]id",['template'=>"{input}"])->hiddenInput()?>
                        <?=$form->field($visibleInput, "[visibleInputs][$key]id_input_visible",['template'=>"{input}"])->dropDownList($visibleInputs,['class'=>'form-control visible-field','prompt'=>'Выберите поле зависимости'])?>
                    </div>
                    <div class="col-sm-6 visibleInputValues">
                        <?=(!empty($visibleInput->id_input_visible))?$this->render('_input',['visibleInput'=>$visibleInput->visibleInput,'model'=>$visibleInput,'form'=>$form,'rowKey'=>$key]):''?>
                    </div>
                    <div class="col-sm-1">
                        <a class="close" href="javascript:">&times;</a>
                    </div>
                </div>
            <?php
                }
            ?>
            </div>

            <a onclick="return addInput('visibles')" href="#" class="btn btn-default btn-visible">Добавить еще</a>
    <?php
        }
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use common\models\FormInputType;
use common\models\FormInput;
use common\models\Collection;
use common\models\CollectionColumn;

use kartik\select2\Select2;
use yii\web\JsExpression;
?>
<div class="form-input-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'formInput-form'
        ]
    ]); ?>

    <?=$form->field($model, 'id_input_copy')->dropDownList(ArrayHelper::map($exist_inputs,'id_input','name'), ['prompt'=>'Выберите поле']);?>

    <h3>Настройка отображения</h3>
    <?php
        echo $this->render('_element_options',['element'=>$model->element,'id_form'=>$model->id_form,'form'=>$form]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
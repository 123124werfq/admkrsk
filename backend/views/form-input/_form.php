<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use common\models\FormInputType;
use common\models\FormInput;

use common\models\Collection;
use common\models\CollectionColumn;


?>
<div class="form-input-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'id' => 'formInput-form'
        ]
    ]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'required')->checkBox()?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'readonly')->checkBox()?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'type')->dropDownList(CollectionColumn::getTypeLabel()) ?>
        </div>
        <div class="col-sm-6">
            <?=$form->field($model, 'id_type')->widget(Select2::class, [
                'data' => ArrayHelper::map(FormInputType::find()->where(['type'=>$model->type])->all(), 'id_type', 'name'),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => 'Поведение поля',
                ],
            ])?>
        </div>
    </div>

    <?=$form->field($model, 'name')->textInput(['maxlength' => 255]) ?>

    <?=$form->field($model, 'label')->textArea()->hint('Жирным шрифтом вверху')?>

    <?=$form->field($model, 'hint')->textArea()->hint('Подсказка внизу серым шрифтом')?>

    <?php
    if ($model->type==CollectionColumn::TYPE_SERVICETARGET)
        echo $form->field($model, 'fieldname')->textInput(['maxlength' => 255,'readonly'=>true,'value'=>'id_target']);
    else
        echo $form->field($model, 'fieldname')->textInput(['maxlength' => 255]);
    ?>

    <?php if ($model->type == CollectionColumn::TYPE_SELECT ||
              $model->type == CollectionColumn::TYPE_RADIO ||
              $model->type == CollectionColumn::TYPE_CHECKBOXLIST ||
              $model->type == CollectionColumn::TYPE_CHECKBOX){?>
        <?=$form->field($model, 'values')->textarea(['rows' => 6])->hint('Вводить через ;')?>
    <?php }?>

    <?php if ($model->type == CollectionColumn::TYPE_SELECT
           || $model->type == CollectionColumn::TYPE_RADIO
           || $model->type == CollectionColumn::TYPE_CHECKBOXLIST
           || $model->type == CollectionColumn::TYPE_COLLECTION
           || $model->type == CollectionColumn::TYPE_COLLECTIONS
       ){?>
        <?=$form->field($model, 'id_collection')->widget(Select2::class, [
            'data' => ArrayHelper::map(Collection::find()->all(), 'id_collection', 'name'),
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => 'Выберите список',
            ],
        ])->label('Или взять данные из списка')?>
    <?php }?>

    <?php if ($model->type == CollectionColumn::TYPE_JSON)
    {
                echo "<br/>
                <h3>Настройки таблицы</h3>";

                $data = $model->getTableOptions();

                echo '<div class="row-flex">';
                foreach ($data[0] as $key => $option)
                    echo '<div class="col"><label class="control-label">'.$option['name'].'</label></div>';
                echo '</div>';

                echo '<div id="table_options" class="multiyiinput">';
                foreach ($data as $key => $row)
                {
                    echo '<div class="row-flex">';
                    foreach ($row as $okey => $option)
                    {
                        $option['class'] = 'form-control';
                        $option['id'] = 'values_'.$okey.'_'.$key;
                        echo '<div class="col">';
                            if (empty($option['values']))
                                echo Html::textInput("FormInput[values][$key][$okey]",$option['value'],$option);
                            else
                                echo Html::dropDownList("FormInput[values][$key][$okey]",$option['value'],$option['values'],$option);
                        echo '</div>';
                    }
                    echo '<div class="col col-close"><a class="close" href="javascript:">&times;</a></div>';
                    echo '</div>';
                }
                echo '</div>';

                echo '<a class="btn btn-default btn-visible" href="javascript:" onclick="return addInput(\'table_options\')">Добавить столбец</a>';
    }?>

    <div id="input-options">
        <?php
            echo $this->render('_options',['model'=>$model]);
        ?>
    </div>

    <br/>
    <h3>Настройка отображения</h3>
    <?php
        echo $this->render('_element_options',['element'=>$model->element,'id_form'=>$model->id_form,'form'=>$form]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

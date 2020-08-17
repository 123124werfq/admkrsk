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

    <?=$form->field($model, 'label')->textArea(['required'=>($model->type==CollectionColumn::TYPE_CHECKBOX)])->hint('Сверху поля ввода или если это чекбокс справо от чекбокса')?>

    <?=$form->field($model, 'hint')->textArea()->hint('Подсказка внизу серым шрифтом')?>

    <?php
    if ($model->type==CollectionColumn::TYPE_SERVICETARGET)
        echo $form->field($model, 'fieldname')->textInput(['maxlength' => 255,'readonly'=>true,'value'=>'id_target']);
    else
        echo $form->field($model, 'fieldname')->textInput(['maxlength' => 255]);
    ?>

    <?php if ($model->type == CollectionColumn::TYPE_CHECKBOX){?>
        <?=$form->field($model, 'values')->textarea(['rows' => 6,'required'=>($model->type==CollectionColumn::TYPE_CHECKBOX)])->label('Введите значение, если чекбокс выбран')?>
    <?php }?>

    <?php if ($model->type == CollectionColumn::TYPE_SELECT ||
              $model->type == CollectionColumn::TYPE_RADIO ||
              $model->type == CollectionColumn::TYPE_CHECKBOXLIST){?>
        <?=$form->field($model, 'values')->widget(Select2::class, [
            'data' => $model->getArrayValues(),
            /*'multiple'=>true,*/
            'pluginOptions' => [
                'allowClear' => true,
                'multiple'=>true,
                'tags'=> true,
                'placeholder' => 'Введите значения',
            ],
            'options' => [
                'value'=>$model->getArrayValues(),
            ]
        ])?>

    <?php }?>

    <?php if ($model->supportCollectionSource()){?>
    <div class="row">
        <div class="col-md-6">
        <?=$form->field($model, 'id_collection')->widget(Select2::class, [
            'data' => ArrayHelper::map(Collection::find()->all(), 'id_collection', 'name'),
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => 'Выберите список',
            ],
        ])->label('Или взять данные из списка')?>
        </div>
        <div class="col-md-6">
        <?=$form->field($model, 'id_collection_column')->widget(Select2::class, [
            'data' => (!empty($model->collectionColumn))?[$model->id_collection_column=>$model->collectionColumn->name]:[],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 0,
                'placeholder' => 'Начните ввод',
                'ajax' => [
                    'url' => '/collection-column/list',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) {return {q:params.term,id_collection:$("#forminput-id_collection").val()}}')
                ],
            ],
            'options' => [
                'value'=>$model->id_collection_column,
            ]
        ])->label('Выберите колонку для отображения')?>
        </div>
    </div>
    <?php }?>

    <?php if ($model->type == CollectionColumn::TYPE_JSON)
    {
        echo "<br/>
        <h3>Настройки таблицы</h3>";

        $data = $model->getTableOptions();

        echo '<div class="row-flex">';
        foreach ($data[0] as $key => $option)
            echo '<div class="col" '.(!empty($option['width'])?'style="width:'.$option['width'].'px;"':'').'><label class="control-label">'.$option['name'].'</label></div>';
        echo '<div class="col col-close"></div></div>';

        echo '<div id="table_options" class="multiyiinput">';
        foreach ($data as $key => $row)
        {
            echo '<div class="row-flex" data-row="'.$key.'">';
            foreach ($row as $okey => $option)
            {
                $option['class'] = 'form-control';
                $option['id'] = 'values_'.$okey.'_'.$key;

                echo '<div class="col" '.(!empty($option['width'])?'style="width:'.$option['width'].'px;"':'').'>';
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

    <?php if ($model->isCopyable()) {
       echo $form->field($model, 'id_input_copy')->dropDownList(ArrayHelper::map($model->form->getInputs()->select(['id_input','name'])->where(['type'=>$model->type])->andWhere('id_input <> '.(int)$model->id_input)->all(),'id_input','name'),['prompt'=>'Выберите поле']);
    }?>
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

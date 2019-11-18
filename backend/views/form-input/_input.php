<?php
	use common\models\CollectionColumn;
	use kartik\select2\Select2;
	use yii\web\JsExpression;
?>
<div class="form-group">
	<?php
		if ($visibleInput->type->type==CollectionColumn::TYPE_SELECT)
	    {
	        $values = $visibleInput->getArrayValues();

	        /*if (!empty($model->visibleInputValue))
	        	$model->visibleInputValue = $model->visibleInputValue->getValue();*/

	        $selected = [];
	        if (is_array($model->visibleInputValue))
		        foreach ($model->visibleInputValue as $key => $value) {
		        	$selected[$value] = ["selected"=>true];
		        };

	        if (!empty($values))
	        {
	        	//echo $form->field($model, 'visibleInputValue[]')->dropDownList($values,['multiple'=>'multiple']);

	        	echo $form->field($model, 'visibleInputValue[]')->widget(Select2::class, [
                    'data' => $values,
                    'pluginOptions' => [
                        'multiple' => true,
                    ],
                    'options'=>[
                    	'multiple'=>'multiple',
                    	'options'=>$selected,
                    ],
                ]);
	        }
	        else if (!empty($model->id_collection))
	        {
	        	echo $form->field($model, 'visibleInputValue[]')->widget(Select2::class, [
                    'data' => $model->visibleInputValue,
                    'pluginOptions' => [
                        'multiple' => true,
                        'allowClear' => true,
                        'minimumInputLength' => 2,
                        'placeholder' => 'Начните ввод',
                        'ajax' => [
                            'url' => '/collection/list',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                    'options'=>['multiple'=>'multiple'],
                ]);
	        }
	    }
	    else
	    {
	    	echo $form->field($model, 'visibleInputValue')->textInput(['value'=>(is_array($model->visibleInputValue))?implode(';',$model->visibleInputValue):'']);
	    }
    ?>
</div>
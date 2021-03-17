<?php
	use common\models\CollectionColumn;
	use kartik\select2\Select2;
	use yii\web\JsExpression;
?>
<div class="form-group">

	<?php
		if ($visibleInput->type==CollectionColumn::TYPE_SELECT ||
			$visibleInput->type==CollectionColumn::TYPE_CHECKBOX ||
			$visibleInput->type==CollectionColumn::TYPE_RADIO ||
			$visibleInput->type==CollectionColumn::TYPE_SERVICETARGET)
	    {
	        $values = $visibleInput->getArrayValues();

	        $selected = [];

	        if (is_array($model->values))
		        foreach ($model->values as $key => $value) {
		        	$selected[$value] = ["selected"=>true];
		        };

	        if (!empty($values))
	        {
	        	//echo $form->field($model, 'FormInput[visibleInputs][values]')->dropDownList($values,['multiple'=>'multiple']);

	        	echo $form->field($model, "[visibleInputs][$rowKey]values",['template'=>"{input}"])->widget(Select2::class, [
                    'data' => $values,
                    'pluginOptions' => [
                        'multiple' => true,
                    ],
                    'options'=>[
                    	'multiple'=>'multiple',
                    	'options'=>$selected,
                    	'id'=>'visibleInputs_values_'.$rowKey
                    ],
                ]);
	        }
	        else if (!empty($model->id_collection))
	        {
	        	echo $form->field($model, "[visibleInputs][$rowKey]values",['template'=>"{input}"])->widget(Select2::class, [
                    'data' => $model->values,
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
                    'options'=>['multiple'=>'multiple','id'=>'visibleInputs_values_'.$rowKey],
                ]);
	        }
	    }
	    else if ($visibleInput->type==CollectionColumn::TYPE_COLLECTION)
	    {
	    	echo $form->field($model, "[visibleInputs][$rowKey]values",['template'=>"{input}"])->widget(Select2::class, [
                'data' => $model->values,
                'pluginOptions' => [
                    'multiple' => false,
                    'allowClear' => true,
                    'minimumInputLength' => 0,
                    'placeholder' => 'Начните ввод',
                    'ajax' => [
                        'url' => '/collection/list',
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],
                'options'=>['multiple'=>'multiple','id'=>'visibleInputs_values_'.$rowKey],
            ]);
	    }
	    else
	    {
	    	echo $form->field($model, "[visibleInputs][$rowKey]values",['template'=>"{input}"])->textInput(['value'=>(is_array($model->values))?implode(';',$model->values):'','id'=>'visibleInputs_values_'.$rowKey]);
	    }
    ?>
</div>
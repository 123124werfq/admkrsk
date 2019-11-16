<?php
	use common\models\CollectionColumn;
?>
<div class="form-group">
	<?php
		if ($model->type->type==CollectionColumn::TYPE_SELECT)
	    {
	        $values = $model->getArrayValues;

	        if (!empty($values))
	        {
	        	echo $form->field($model, 'visibleInputValue')->dropDownList($values);
	        }
	        else if (!empty($model->id_collection))
	        {
	        	echo $form->field($model, 'visibleInputValue')->widget(Select2::class, [
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
<?php
	use yii\widgets\ActiveForm;
?>
<div class="boxed form-inside">
	<?php $activeForm = ActiveForm::begin([
		'fieldConfig' => [
	        'template' => '{input}{error}',
	    ],
		'options'=>[
			'enctype'=>'multipart/form-data'
		]
	]); ?>
	<?php foreach ($form->rows as $key => $row)
	{
		echo '<div class="row">';
		foreach ($row->elements as $key => $element)
		{
			if (!empty($element->id_input))
				echo $this->render('_input',['input'=>$element->input,'model'=>$model,'form'=>$activeForm]);
			elseif (!empty($element->content))
				echo '<div class="text-row">'.$element->content.'</div>';
		}
		echo '</div>';
	}
	?>
	<div class="form-end">
        <div class="form-end_right">
            <input type="submit" class="btn btn__secondary" value="Отправить">
        </div>
    </div>
	<?php ActiveForm::end(); ?>
</div>
<?php
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;

	$this->registerJsFile('/js/dropzone/dropzone.js',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);

	/*$this->registerJsFile('/js/fileuploader/dropzone_multiupload.js',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);*/
	$this->registerCssFile('/js/dropzone/dropzone.min.css');
?>
<div class="boxed form-inside">
	<?php $activeForm = ActiveForm::begin([
		'action'=>($action===null)?'/form/create?id='.$form->id_form:$action,
		'fieldConfig' => [
	        'template' => '{input}{error}',
	    ],
		'options'=>[
			'enctype'=>'multipart/form-data'
		]
	]); ?>

	<?php foreach ($inputs as $name => $value)
			echo Html::hiddenInput($name,$value);
	?>

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
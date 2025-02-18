<?php
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
	use common\models\FormVisibleInput;
	use yii\captcha\CaptchaValidator;

	$this->registerJsFile('/js/dropzone/dropzone.js',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);
	$this->registerCssFile('/js/dropzone/dropzone.min.css');	

	$visibleElements = [];
	$visibleInputs = [];

	$visibleInputsModels = FormVisibleInput::find()->joinWith(['visibleInput'])->where(['id_form'=>$form->id_form])->all();

	foreach ($visibleInputsModels as $vkey => $vinput)
	{
		$visibleInputs[$vinput->id_input_visible][$vinput->id_element] = $vinput->id_element;
		$visibleElements[$vinput->id_element][$vinput->id_input_visible] = $vinput->values;
	}
?>
<div class="boxed form-inside">
<?php if (!empty($visibleInputs)){

$visibleInputs = json_encode($visibleInputs);
$visibleElements = json_encode($visibleElements);

$script = <<< JS
var visibleInputs = $visibleInputs;
var visibleElements = $visibleElements;

$(document).ready(function() {
	visibleForm(visibleInputs,visibleElements,'#form$form->id_form');
});
JS;

$this->registerJs($script, yii\web\View::POS_END);
}?>
	<?php $activeForm = ActiveForm::begin([
		'id'=>'form'.$form->id_form,
		'action'=>($action===null)?'/form/create?id='.$form->id_form:$action,
		'enableAjaxValidation'=>false,
		'enableClientValidation'=>true,
		'fieldConfig' => [
	        'template' => '{input}{error}',
	    ],
		'options'=>[
			'enctype'=>'multipart/form-data',
			'class'=>'ajax-form',
			'data-maxfilesize'=>($form->maxfilesize)?$form->maxfilesize*1024*1024:10*1024*1024,
		]
	]); ?>

	<?php foreach ($inputs as $name => $value)
			echo Html::hiddenInput($name,$value);
	?>

	<?=$this->render('_rows',[
		'rows'=>$form->rows,
		'model'=>$model,
		'activeForm'=>$activeForm,
		'modelForm'=>$form,
		'arrayGroup'=>$arrayGroup
	])?>

	<?php if ($form->needCaptcha())
	{
		echo '<label class="form-label form-label__second">Защита от спама<span class="red">*</span></label>';
		echo $activeForm->field($model, 'captcha')->widget(\yii\captcha\Captcha::classname(), ['template'=>'<div class="row"><div style="width:50%; text-align:center;">{image}</div><div style="width:50%;">{input}<p class="text-help mb-3">пожалуйста, введите символы на картинке</p></div></div>']);
	}
	?>

	<div class="form-end">
        <div class="form-end_right">
            <input type="submit" class="btn btn__secondary" value="<?=$submitLabel?>">
        </div>
    </div>
	<?php ActiveForm::end(); ?>
</div>
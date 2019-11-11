<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model common\models\Block */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textarea(['rows' => 6,'class'=>'redactor']) ?>

    <?php
    if (!empty($model->blocks[$model->type]['vars']))
    {
    	$vars = $model->getVars();

    	foreach ($vars as $ckey => $var) {
    		echo '<div class=form-group>';
    		switch ($var->type) {
    			case $var::TYPE_HIDDEN:
	                echo Html::activeHiddenInput($var,"[$ckey]value",['class'=>'form-control','id'=>'Value_'.$ckey]);
	                break;
	            case $var::TYPE_STRING:
	                echo Html::activeTextInput($var,"[$ckey]value",['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$var->name]);
	                break;
	            case $var::TYPE_TEXT:
	                echo Html::activeTextArea($var,"[$ckey]value",['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$var->name]);
	                break;
	            case $var::TYPE_CHECKBOX:
	                echo Html::activeCheckBox($var,"[$ckey]value",['id'=>'Value_'.$ckey,'label'=>$var->name]);
	                break;
	            case $var::TYPE_MENU:
	                echo Html::activeDropDownList($var,"[$ckey]value",ArrayHelper::map(\common\models\Menu::find()->all(), 'id_menu', 'name'),['class'=>'form-control','id'=>'Value_'.$ckey,'prompt'=>$var->name]);
	                break;
				case $var::TYPE_QUESTION:
	                echo Html::activeDropDownList($var,"[$ckey]value",ArrayHelper::map(\common\models\Question::find()->all(), 'id_poll_question', 'question'),['class'=>'form-control','id'=>'Value_'.$ckey, 'prompt'=>$var->name]);
	                break;
	            case $var::TYPE_PAGE:
	            	echo $form->field($var, "[$ckey]value")->widget(Select2::class, [
	                    'data' => ArrayHelper::map(\common\models\Page::find()->all(), 'id_page', 'title'),
	                    'pluginOptions' => [
	                        'allowClear' => true,
	                        'placeholder' => 'Выберите раздел',
	                    ],
                	]);

	                //echo Html::activeDropDownList($var,"[$ckey]value",ArrayHelper::map(\common\models\Page::find()->all(), 'id_page', 'title'),['class'=>'form-control','id'=>'Value_'.$ckey, 'prompt'=>$var->name]);

	                break;
	            case $var::TYPE_RICHTEXT:
	                echo Html::activeTextArea($var,"[$ckey]value",['class'=>'form-control','id'=>'Value_'.$ckey,'placeholder'=>$var->name,'redactor'=>true]);
	                break;
	            case $var::TYPE_MEDIA:
	            	echo Html::activeHiddenInput($var,"[$ckey]value");
	                echo common\components\multifile\MultiFileWidget::widget([
			            'model'=>$var,
			            'single'=>true,
			            'relation'=>'media',
			            'extensions'=>['jpg','jpeg','gif','png'],
			            'grouptype'=>1,
			            'showPreview'=>true
			        ]);
	                break;
	            case $var::TYPE_MEDIAS:
	            	echo Html::activeHiddenInput($var,"[$ckey]value");
	                echo common\components\multifile\MultiFileWidget::widget([
			            'model'=>$var,
			            'single'=>false,
			            'relation'=>'medias',
			            'extensions'=>['jpg','jpeg','gif','png'],
			            'grouptype'=>$ckey,
			            'showPreview'=>true
			        ]);
	                break;
	        }
	        echo "</div>";
    	}
    }?>
    <hr>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
    </div>
</div>

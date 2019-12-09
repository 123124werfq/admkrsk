<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FormElement */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
	'id'=>'ElementForm'
]); ?>

<?php
    echo $this->render('/form-input/_element_options',['element'=>$model,'form'=>$form, 'id_form'=>$id_form]);
?>

<?php if (empty($model->id_form)){?>
	<?=$form->field($model, 'content')->textarea(['rows' => 6,'class'=>'form-control redactor'])?>
<?php }?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
	tinymce.init(tinymceConfig);
</script>

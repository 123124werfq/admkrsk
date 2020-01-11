<?php

use backend\widgets\UserActiveDirectoryMapEsia;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */

$model->roles = $model ? ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($model->id), 'name') : [];
?>

<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'status')->dropDownList(User::getStatusNames()) ?>

	<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'fullname')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

	<?= $form->field($model, 'description')->textArea() ?>

	<h3>Фотография</h3>

	<?=common\components\multifile\MultiFileWidget::widget([
	    'model'=>$model,
	    'single'=>true,
	    'relation'=>'media',
	    'extensions'=>['jpg','jpeg','gif','png'],
	    'grouptype'=>1,
	    'showPreview'=>true
	]);?>

    <?php if ($model->id_ad_user || empty($model->id_esia_user)): ?>
        <?= $form->field($model, 'esia_user')->label('Связь с пользователем ЕСИА')->widget(UserActiveDirectoryMapEsia::class) ?>
    <?php endif; ?>

	<?= $form->field($model, 'roles[]')->hiddenInput() ?>

	<?= $form->field($model, 'roles')->label(false)->checkboxList(User::getRoleNames(), [
	    'item' => function($index, $label, $name, $checked, $value) {
	        return '<label class="col-md-3"><input type="checkbox" name="' . $name . '" value="' . $value . '"' . ($checked ? ' checked' : '') . '>' . $label . '</label>';
	    },
	    'class' => 'row',
	]) ?>
	<hr>
	<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>

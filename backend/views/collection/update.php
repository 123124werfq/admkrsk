<?php

use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\Collection */

$this->title = 'Редактирование списка: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_collection]];
$this->params['breadcrumbs'][] = 'Связывание';
?>
<div class="collection-update">
    <div class="ibox">
        <div class="ibox-content">
        	<?php $form = ActiveForm::begin(); ?>

        	<?=$form->field($model, 'alias')->textInput(['class' => 'form-control'])?>

        	<?=$form->field($model, 'column_name')->textInput(['class' => 'form-control'])?>

        	<?=$form->field($model, 'id_collection_column')->widget(Select2::class, [
		        	'data' => ArrayHelper::map($collection->columns,'id_column','name'),
		        	'pluginOptions' => [
		            'allowClear' => true,
		            'multiple' => false,
		            'placeholder' => 'Выберите колонку',
		        ],
		        'options'=>['multiple' => true,]
		    ])->hint('Выберите колонку с ключем / ключами')?>

        	<?= Html::submitButton('Связать', ['class' => 'btn btn-success']) ?>

			<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
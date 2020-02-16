<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use common\models\CollectionColumn;
use common\models\Collection;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Collection */

$this->title = 'Связь списка: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_collection]];
$this->params['breadcrumbs'][] = 'Связывание';
?>
<div class="collection-update">
    <div class="ibox">
        <div class="ibox-content">
        	<?php $form = ActiveForm::begin(); ?>

            <h3>Настройки для новой колонки</h3><br/>

        	<?=$form->field($formAssign, 'alias')->textInput(['class' => 'form-control'])?>

        	<?=$form->field($formAssign, 'column_name')->textInput(['class' => 'form-control'])?>

            <?=$form->field($formAssign, 'type')->dropDownList([
                CollectionColumn::TYPE_COLLECTION=>'Данные из списка, один элемент',
                CollectionColumn::TYPE_COLLECTIONS=>'Данные из списка, несколько элементов',
            ])?>

            <hr>

            <div class="row">
                <div class="col-md-6">

            	<?=$form->field($formAssign, 'id_collection_column')->widget(Select2::class, [
    		        	'data' => ArrayHelper::map($model->columns,'id_column','name'),
    		        	'pluginOptions' => [
        		            'allowClear' => true,
        		            'multiple' => false,
        		            'placeholder' => 'Выберите колонку',
        		        ],
    		    ])->hint('Выберите колонку с ключем / ключами')?>

                </div>
                <div class="col-md-6">
                    <?=$form->field($formAssign, 'id_collection_from')->widget(Select2::class, [
                            'data' => ArrayHelper::map(Collection::find()->all(),'id_collection','name'),
                            'pluginOptions' => [
                            'allowClear' => true,
                            'multiple' => false,
                            'placeholder' => 'Выберите список',
                        ],
                    ])?>
                
                    <?= $form->field($formAssign, 'id_collection_from_column')->widget(Select2::class, [
                            'data' => [],
                            'pluginOptions' => [
                                'multiple' => false,
                                'allowClear' => true,
                                'minimumInputLength' => 0,
                                'placeholder' => 'Начните ввод',
                                'ajax' => [
                                    'url' => '/collection-column/list',
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) {return {q:params.term,id_collection:$("#collectioncombineform-id_collection_from").val()}}')
                                ],
                            ],
                        ])?>

                        <?= $form->field($formAssign, 'id_collection_from_column_label')->widget(Select2::class, [
                            'data' => [],
                            'pluginOptions' => [
                                'multiple' => false,
                                'allowClear' => true,
                                'minimumInputLength' => 0,
                                'placeholder' => 'Начните ввод',
                                'ajax' => [
                                    'url' => '/collection-column/list',
                                    'dataType' => 'json',
                                    'data' => new JsExpression('function(params) {return {q:params.term,id_collection:$("#collectioncombineform-id_collection_from").val()}}')
                                ],
                            ],
                        ])?>
                </div>
            </div>

        	<?= Html::submitButton('Связать', ['class' => 'btn btn-success']) ?>

			<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
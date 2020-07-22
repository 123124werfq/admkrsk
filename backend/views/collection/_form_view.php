<?php

use backend\widgets\UserAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

use common\models\CollectionColumn;
use common\models\Collection;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\Collection */
/* @var $form yii\widgets\ActiveForm */

$columns = ArrayHelper::map($model->parent->columns, 'id_column', 'name');
$model->filters = $rules = $model->getViewFilters();
$model->filters = json_encode($model->filters);

?>

<?php $form = ActiveForm::begin(['id'=>'collection-view']); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<hr>
<?= $form->field($model, 'template')->textInput(['class' => 'form-control redactor']) ?>

<?= $form->field($model, 'template_element')->textInput(['class' => 'form-control redactor']) ?>

<?php if (!$model->isNewRecord) { ?>
    <?= $form->field($model, 'label')->widget(Select2::class, [
        'data' => ArrayHelper::map($model->getColumns()->andWhere([
            'id_collection' => $model->id_collection,
            'type' => [CollectionColumn::TYPE_INPUT, CollectionColumn::TYPE_INTEGER]
        ])->all(), 'id_column', 'name'),
        'pluginOptions' => [
            'allowClear' => true,
            'multiple' => true,
            'placeholder' => 'Выберите колонки',
        ],
        'options' => ['multiple' => true,]
    ])->hint('Выберите колонки из которых будет составляться представление для отображения в списках') ?>

    <?= $form->field($model, 'id_column_map')->dropDownList(
        ArrayHelper::map($model->getColumns()->andWhere([
                'id_collection' => $model->id_collection,
        ])->andWhere(['or',['type' => CollectionColumn::TYPE_MAP],['type' => CollectionColumn::TYPE_ADDRESS]])->all(),'id_column','name'),['prompt'=>'Выберите колонку'])->hint('В колонки должны содержаться координаты');
    ?>
<?php } ?>

<br/><br/>
<h3>Поля</h3>
<br/>
<div class="row">
    <div class="col-md-2">
        <label class="control-label">Колонка</label>
    </div>
</div>
<div id="view-columns" class="multiyiinput sortable">
    <?php foreach ($model->getViewColumns() as $key => $data) {?>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <?=Html::dropDownList("ViewColumns[$key][id_column]",$data['id_column'],$columns,['class'=>'form-control','id'=>'CollectionColumn_id_column_'.$key,'placeholder'=>'Выберите колонку']);?>
                </div>
            </div>
            <div class="col-md-1 col-close">
                <a class="close btn" href="#">&times;</a>
            </div>
        </div>
    <?php }?>
</div>
<a onclick="return addInput('view-columns')" href="#" class="btn btn-default">Добавить еще</a>

<br/><br/>
<h3>Условия фильтрации</h3>

<?=$form->field($model, 'filters')->hiddenInput()->label(false);?>

<div id="querybuilder"></div>

<?php if (Yii::$app->user->can('admin.collection')): ?>
    <hr>
    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'access_user_ids')->widget(UserAccessControl::class) ?>
<?php endif; ?>


<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

<?php
    $json_filters = [];
    foreach ($model->parent->columns as $key => $column) {
        $json_filters[] = $column->getJsonQuery();
    }

    $json_filters = json_encode($json_filters);

    if (!empty($rules))
        $rules = json_encode($rules);
    else
        $rules = '';

$script = <<< JS
    $("#collection-view").submit(function(){
        var rules = $('#querybuilder').queryBuilder('getMongo');
        $("#collection-filters").val(JSON.stringify(rules));
    });

    $('#querybuilder').queryBuilder({
      lang_code: 'ru',
      filters: $json_filters,
    });

    if ('$rules'!='')
        $('#querybuilder').queryBuilder('setRulesFromMongo',$rules);
JS;

$this->registerJs($script, View::POS_END);
?>
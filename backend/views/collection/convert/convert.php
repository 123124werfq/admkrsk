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

$this->title = 'Конвертация типов данных: ' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_collection]];
?>
<div class="collection-update">
    <div class="ibox">
        <div class="ibox-content">
        	<?php $form = ActiveForm::begin(); ?>

            <h3>Какую колонку добавить</h3><br/>

            <?=$form->field($formConvert, 'type')->dropDownList([
                CollectionColumn::TYPE_MAP=>'Координаты',
                CollectionColumn::TYPE_ADDRESS=>'Адрес',
                CollectionColumn::TYPE_REPEAT=>'Адрес с повтром',
                //CollectionColumn::TYPE_COLLECTIONS=>'Данные из списка, несколько элементов',
            ])?>

            <div class="form-group">
                <label>X</label>
                <?=Html::dropDownList('x','',
                    ArrayHelper::map($columns,'alias','name'),
                    ['class'=>'form-control']
                )?>
            </div>
            <div class="form-group">
                <label>Y</label>
                <?=Html::dropDownList('y','',
                    ArrayHelper::map($columns,'alias','name'),
                    ['class'=>'form-control']
                )?>
            </div>

            <div class="form-group">
                <label>Адрес</label>
                <?=Html::dropDownList('address','',
                    ArrayHelper::map($columns,'alias','name'),
                    ['class'=>'form-control','prompt'=>'Выберите колонку']
                )?>
            </div>

            <hr>
        	<?= Html::submitButton('Создать новое поле', ['class' => 'btn btn-success']) ?>

			<?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Collection;
use common\models\Page;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model common\models\Project */
/* @var $form yii\widgets\ActiveForm */

$types = Collection::find()->where(['alias'=>"project_type"])->one();

if (!empty($types))
    $types = $types->getArray();
else
    $types = [];
?>

<div class="ibox">
    <div class="ibox-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList($types,['prompt'=>'Выберите тип']) ?>

    <div class="row">
        <div class="col-sm-6">
             <?=$form->field($model, 'id_page')->widget(Select2::class, [
                'data' => ArrayHelper::map(Page::find()->all(), 'id_page', 'title'),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => 'Выберите родительский раздел',
                ],
            ])?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'date_begin')->textInput(['type'=>'datetime-local','value'=>(!empty($model->date_begin))?date('Y-m-d\TH:i:s', $model->date_begin):'']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'date_end')->textInput(['type'=>'datetime-local','value'=>(!empty($model->date_end))?date('Y-m-d\TH:i:s', $model->date_end):'']) ?>
        </div>
    </div>

    <?= common\components\multifile\MultiFileWidget::widget([
            'model'=>$model,
            'single'=>true,
            'relation'=>'media',
            //'records'=>[$value_model->media],
            'extensions'=>['jpg','jpeg','gif','png'],
            'grouptype'=>1,
            'showPreview'=>true
        ]);?>

    <hr>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
    </div>
</div>


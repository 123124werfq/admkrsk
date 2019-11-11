<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\ServiceRubric;

/* @var $this yii\web\View */
/* @var $model backend\models\search\ServiceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'fullname') ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'reestr_number') ?>
        </div>
        <div class="col-sm-3">
            <?=$form->field($model, 'id_rub')->widget(Select2::class, [
                'data' => ArrayHelper::map(ServiceRubric::find()->joinWith('childs as childs')->where('childs.id_rub IS NULL')->all(), 'id_rub', 'name'),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => 'Выберите рубрику',
                ],
            ])?>
        </div>
        <div class="col-sm-3" style="padding-top: 30px;">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('Сбросить', ['class' => 'btn btn-outline-secondary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
</div>
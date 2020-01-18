<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Collection;

use yii\helpers\ArrayHelper;

use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppealForm */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="ibox">
    <div class="ibox-content">

    <?php $form = ActiveForm::begin(); ?>

    <?=$form->field($model, 'id_form')->widget(Select2::class, [
        'data' => ArrayHelper::map(\common\models\Form::find()->where(['is_template'=>0])->all(), 'id_form', 'name'),
        'pluginOptions' => [
            'allowClear' => true,
            'placeholder' => 'Выберите форму',
        ],
    ])?>

	<?=$form->field($model, 'id_record_firm')->widget(Select2::class, [
        'data' => Collection::getArrayByAlias("appeal_firms"),
        'pluginOptions' => [
            'allowClear' => true,
            'placeholder' => 'Выберите организацию',
        ],
    ])?>

    <?=$form->field($model, 'id_record_category')->widget(Select2::class, [
        'data' => Collection::getArrayByAlias("appeal_categories"),
        'pluginOptions' => [
            'allowClear' => true,
            'placeholder' => 'Выберите организацию',
        ],
    ])?>

    <?php if (!$model->isNewRecord){?>
        <?= $form->field($model, "id_service")->widget(Select2::class, [
                'data' => ArrayHelper::map(\common\models\Service::find()->all(), 'id_service', 'reestr_number'),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => 'Выберите услугу',
                ],
            ]);
        ?>
    <?php }else {?>
        <?= $form->field($model, "id_services")->widget(Select2::class, [
                'data' => ArrayHelper::map(\common\models\Service::find()->all(), 'id_service', 'reestr_number'),
                'pluginOptions' => [
                    'multiple' => true,
                    'allowClear' => true,
                    'placeholder' => 'Выберите услуги',
                ],
                'options'=>[
                    'multiple' => true,
                ]
            ]);
        ?>
    <?php }?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppealForm */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="service-appeal-form-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_form')->textInput() ?>

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

    <?= $form->field($model, "id_service")->widget(Select2::class, [
            'data' => ArrayHelper::map(\common\models\Service::find()->all(), 'id_service', 'reestr_number'),
            'pluginOptions' => [
                'allowClear' => true,
                'placeholder' => 'Выберите услугу',
            ],
        ]);
    ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Collection;
use common\models\Page;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;
use common\models\HrExpert;
use common\models\HrProfile;


?>


<div class="ibox">
    <div class="ibox-content">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput() ?>

        <?= $form->field($model, 'begin')->widget(DateTimePicker::class, [
            'type' => DateTimePicker::TYPE_INPUT,
            'convertFormat' => true,
            'options' => [
                'value' => $model->begin ? Yii::$app->formatter->asDatetime($model->begin) : Yii::$app->formatter->asDatetime('+1 day 6:00'),
                'autocomplete' => 'off',
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.MM.yyyy HH:mm',
            ]
        ]) ?>

        <?= $form->field($model, 'end')->widget(DateTimePicker::class, [
            'type' => DateTimePicker::TYPE_INPUT,
            'convertFormat' => true,
            'options' => [
                'value' => $model->end ? Yii::$app->formatter->asDatetime($model->end) : Yii::$app->formatter->asDatetime('+1 month +1 day 23:59'),
                'autocomplete' => 'off',
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.MM.yyyy HH:mm',
            ]
        ]) ?>

        <?=
        $form->field($model, 'id_user')
            ->dropDownList(ArrayHelper::map(HrExpert::find()->where(['state' => 1])->all(),'id_expert','name'));
        ?>

        <?=
            $form->field($model, 'experts[]')
                ->dropDownList(ArrayHelper::map(HrExpert::find()->where(['state' => 1])->all(),'id_expert','name'),
                    [
                        'multiple'=>'multiple',
                    ]);
        ?>

        <?=
            $form->field($model, 'profiles[]')
                ->dropDownList(ArrayHelper::map(HrProfile::find()->where(['reserve_date' => null])->all(),'id_profile','name'),
                    [
                        'multiple'=>'multiple',
                    ]);
        ?>


        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
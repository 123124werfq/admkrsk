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

        <?= $form->field($model, 'name')->textInput() ?>

        <?= $form->field($model, 'date_start')->widget(DateTimePicker::class, [
            'type' => DateTimePicker::TYPE_INPUT,
            'convertFormat' => true,
            'options' => [
                'value' => $model->date_start ? Yii::$app->formatter->asDatetime($model->date_start) : Yii::$app->formatter->asDatetime('+1 day 6:00'),
                'autocomplete' => 'off',
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.MM.yyyy HH:mm',
            ]
        ]) ?>

        <?= $form->field($model, 'date_end')->widget(DateTimePicker::class, [
            'type' => DateTimePicker::TYPE_INPUT,
            'convertFormat' => true,
            'options' => [
                'value' => $model->date_end ? Yii::$app->formatter->asDatetime($model->date_end) : Yii::$app->formatter->asDatetime('+1 month +1 day 23:59'),
                'autocomplete' => 'off',
            ],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.MM.yyyy HH:mm',
            ]
        ]) ?>

        <?=
        $form->field($model, 'moderator')
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

        <?=
        $form->field($model, 'state')
            ->dropDownList(
                [
                    \common\models\HrContest::STATE_NOT_STARTED => 'Не начато' ,
                    \common\models\HrContest::STATE_STARTED => 'Текущее',
                    \common\models\HrContest::STATE_CLOSED => 'Подводятся итоги',
                    \common\models\HrContest::STATE_FINISHED => 'Итоги подведены'
                ]);
        ?>


        <?= $form->field($model, 'notification')->textarea(['rows' => 6, 'class'=>'redactor']) ?>

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
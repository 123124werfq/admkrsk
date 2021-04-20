<?php

use common\models\{HrExpert,HrProfile};
use backend\widgets\{UserAccessControl,UserGroupAccessControl};
use yii\helpers\{Html,ArrayHelper,Url};
use kartik\select2\Select2;
use kartik\datetime\DateTimePicker;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

?>


<div class="ibox">
    <div class="ibox-content">

        <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
        ]); ?>

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


        <?php
            $expertsSelected = [];

            foreach ($model->experts as $expert)
            {
                $expertsSelected[$expert->id_expert] = ['Selected' => true];
            }

            $palinExpertsList = implode(",", array_keys($expertsSelected));

        ?>

        <h3>Эксперты</h3>
        <?= Select2::widget([
            'model' => $model,
            //'attribute' => 'experts[]',
            'data' => ArrayHelper::map(HrExpert::find()->where("id_expert IN ($palinExpertsList)")->all(), 'id_expert', function ($model) {
                return $model->name . ' (' . $model->user->email . ')';
            }),
            'name' => 'experts',
            'value' => !empty($model->experts)?ArrayHelper::map($model->experts, 'id_expert', function ($model) {
                return $model->id_expert;
                //return $model->name . ' (' . $model->user->email . ')';
            }):null,
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => true,
                'minimumInputLength' => 1,
                'placeholder' => 'Выберите экспертов',
                'ajax' => [
                    'url' => Url::toRoute(['/reserve/expertslist']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
            ],
            'options' => [
                'class' => 'col-md-9',
                'multiple' => true,
            ]           
        ]) ?>        

        <br><br>


        <?php
            $profilesSelected = [];

            foreach ($model->profiles as $profileSelected)
            {
                $profilesSelected[$profileSelected->id_profile] = ['Selected' => true];
            }

            $palinProfileList = implode(",", array_keys($profilesSelected));

        ?>

        <h3>Анкеты</h3>
        <?= Select2::widget([
            'model' => $model,
            'data' => ArrayHelper::map(HrProfile::find()->where("id_profile IN ($palinProfileList)")->andWhere(['reserve_date' => null])->all(),'id_profile', function($model){
                if(empty($model->name))
                {
                    $data = $model->getRecordData();
                    return $data['surname'].' '.$data['name'].' '.$data['parental_name'].' ('.$data['email'].')';
                }
                else
                    return $model->name;
            }),
            'name' => 'profiles',
            'value' => !empty($model->profiles)?ArrayHelper::map($model->profiles, 'id_profile', function ($model) {
                return $model->id_profile;            
            }):null,
            'pluginOptions' => [
                'allowClear' => true,
                'multiple' => true,
                'minimumInputLength' => 1,
                'placeholder' => 'Выберите анкеты',
                'ajax' => [
                    'url' => Url::toRoute(['/reserve/profilelist']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
            ],
            'options' => [
                'class' => 'col-md-9',
                'multiple' => true,
            ]           
        ]) ?>                  

        <?=
        $form->field($model, 'state')
            ->dropDownList(
                [
                    \common\models\HrContest::STATE_NOT_STARTED => 'Не начато' ,
                    \common\models\HrContest::STATE_STARTED => 'Текущее',
                    \common\models\HrContest::STATE_CLOSED => 'Подводятся итоги',
                    \common\models\HrContest::STATE_FINISHED => 'Итоги подведены'
                ]);

            if(empty($model->notification))
            {
                $model->notification = "Приглашаем вас принять участие в работе экспертной комиссии кадрового резерва с \n ".Yii::$app->formatter->asDatetime($model->begin)." по ".Yii::$app->formatter->asDatetime($model->end);
                $model->notification .= "\n Ссылка на интерфейс голосования (только для залогиненых): https://t1.admrks.ru/reserve/vote";
            }
        ?>

        <?= $form->field($model, 'notification')->textarea(['rows' => 6, 'class'=>'redactor']) ?>


        <div class="form-group field-hrcontest-title">
            <label class="control-label">Ареса для отправки приглашений</label>
            <?php

            foreach($model->experts as $expert)
            {
        ?>
                <br><input type="checkbox" checked=checked name="HrContest[mailstosend][]" value="<?=($expert->user->esiainfo?$expert->user->esiainfo->email:$expert->user->email)?>">
        <?php
                echo $expert->name . " [" . ($expert->user->esiainfo?$expert->user->esiainfo->email:$expert->user->email)."]";
            }
        ?>
        </div>


        <?= Html::submitButton('Сохранить без отправки приглашений', ['class' => 'btn btn-success']) ?>
        <?= Html::submitButton('Сохранить и отправить письма', ['class' => 'btn btn-info', 'value' => 'sendmail']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
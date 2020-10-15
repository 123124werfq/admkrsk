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
use common\models\CstExpert;
use yii\web\JsExpression;


?>


<div class="ibox">
    <div class="ibox-content">

        <h2><?=$model['name']?></h2>

        <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'method' => 'GET'
        ]); ?>

        <input type="hidden" value="1" name="flag">

        <div class="form-group field-hrcontest-experts">
            <label class="control-label" for="hrcontest-experts">Эксперты</label>
            <?php 

                echo Select2::widget([
                    'data' => ArrayHelper::map(CstExpert::find()->where(['state' => 1])->all(),'id_expert','name'),
                    'name'=>'experts',
                    'value' => $experts,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'multiple' => true,
                        'placeholder' => 'Выберите экспертов',
                    ],
                    'options' => [
                        'multiple' => true,
                    ]
                ]);
            ?>

        </div>

        <div class="form-group field-hrcontest-experts">
            <label class="control-label" for="hrcontest-experts">Сcылка для экспертов</label>
            <p><a href='https://grants.admkrsk.ru/contest/vote/<?=md5($id)?>'>https://grants.admkrsk.ru/contest/vote/<?=md5($id)?></a>
        </div>

        <div class="form-group field-hrcontest-experts">
            <label class="control-label" for="hrcontest-experts">Информационное сообщение</label>
            <p>Шаблоны: {name} - имя эксперта, {contest} - наименование конкурса, {link} - ссылка на конкурс</p>
            <?= Html::textArea('comment', $comment, ['rows' => 6, 'class'=>'redactor']) ?>
            <input type='submit' name='sendmsg' value="Отправить сообщение" class='btn btn-warning' onclick="alert('Извините, в данный момент потчовый сервис недоступен. Попробуйте отправить чуть позже.'); return false;">
        
        </div>

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
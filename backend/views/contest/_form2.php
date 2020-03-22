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

            <?=
                Html::dropDownList('experts', $experts, ArrayHelper::map(CstExpert::find()->where(['state' => 1])->all(),'id_expert','name'),
                        [
                            'class'=>"form-control",
                            'multiple'=>'multiple',
                            //'options' => $expertsSelected
                        ]);
            ?>

        </div>

        <div class="form-group field-hrcontest-experts">
            <label class="control-label" for="hrcontest-experts">Сcылка для экспертов</label>
            <p><a href=""></a>
        </div>

        <div class="form-group field-hrcontest-experts">
            <label class="control-label" for="hrcontest-experts">Информационное сообщение</label>

            <?= Html::textArea('comment', $comment, ['rows' => 6, 'class'=>'redactor']) ?>
        
        </div>

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php

use backend\models\forms\FiasUpdateSettingForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $settingForm FiasUpdateSettingForm */

?>
<div class="ibox">
    <div class="ibox-title">
        <h5>Настройки обновления</h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-<?= $settingForm->hasErrors() ? 'up' : 'down' ?>"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content"<?= $settingForm->hasErrors() ? '' : ' style="display: none"' ?>>
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">
                            <?= Html::activeLabel($settingForm, 'minutes')?>
                        </div>
                        <div class="row">
                            <?php if ($settingForm->hasErrors('selectedMinutes')): ?>
                                <div class="col-sm-12 has-error">
                                    <span class="help-block"><?= Html::error($settingForm, 'selectedMinutes') ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="col-sm-5">
                                <?= Html::activeRadioList($settingForm, 'minutes', FiasUpdateSettingForm::MINUTES, [
                                    'item' => function ($index, $label, $name, $checked, $value) {
                                        return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                                    },
                                ])?>
                            </div>
                            <div class="col-sm-3 text-right">
                                <?= Html::radio(Html::getInputName($settingForm, 'minutes'), $settingForm->minutes == 'select', [
                                    'value' => 'select',
                                    'label' => 'Выберите:',
                                ])?>
                            </div>
                            <div class="col-sm-4">
                                <?= Html::activeDropDownList($settingForm, 'selectedMinutes', range(0, 59), [
                                    'multiple' => true,
                                    'size' => 10,
                                    'style' => 'width: 50px;',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">
                            <?= Html::activeLabel($settingForm, 'hours')?>
                        </div>
                        <div class="row">
                            <?php if ($settingForm->hasErrors('selectedHours')): ?>
                                <div class="col-sm-12 has-error">
                                    <span class="help-block"><?= Html::error($settingForm, 'selectedHours') ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="col-sm-5">
                                <?= Html::activeRadioList($settingForm, 'hours', FiasUpdateSettingForm::HOURS, [
                                    'item' => function ($index, $label, $name, $checked, $value) {
                                        return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                                    },
                                ])?>
                            </div>
                            <div class="col-sm-3 text-right">
                                <?= Html::radio(Html::getInputName($settingForm, 'hours'), $settingForm->hours == 'select', [
                                    'value' => 'select',
                                    'label' => 'Выберите:',
                                ])?>
                            </div>
                            <div class="col-sm-4">
                                <?= Html::activeDropDownList($settingForm, 'selectedHours', range(0, 23), [
                                    'multiple' => true,
                                    'size' => 10,
                                    'style' => 'width: 50px;',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">
                            <?= Html::activeLabel($settingForm, 'days')?>
                        </div>
                        <div class="row">
                            <?php if ($settingForm->hasErrors('selectedDays')): ?>
                                <div class="col-sm-12 has-error">
                                    <span class="help-block"><?= Html::error($settingForm, 'selectedDays') ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="col-sm-5">
                                <?= Html::activeRadioList($settingForm, 'days', FiasUpdateSettingForm::DAYS, [
                                    'item' => function ($index, $label, $name, $checked, $value) {
                                        return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                                    },
                                ])?>
                            </div>
                            <div class="col-sm-3 text-right">
                                <?= Html::radio(Html::getInputName($settingForm, 'days'), $settingForm->days == 'select', [
                                    'value' => 'select',
                                    'label' => 'Выберите:',
                                ])?>
                            </div>
                            <div class="col-sm-4">
                                <?= Html::activeDropDownList($settingForm, 'selectedDays', FiasUpdateSettingForm::SELECTED_DAYS, [
                                    'multiple' => true,
                                    'size' => 10,
                                    'style' => 'width: 50px;',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">
                            <?= Html::activeLabel($settingForm, 'months')?>
                        </div>
                        <div class="row">
                            <?php if ($settingForm->hasErrors('selectedMonths')): ?>
                                <div class="col-sm-12 has-error">
                                    <span class="help-block"><?= Html::error($settingForm, 'selectedMonths') ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="col-sm-5">
                                <?= Html::activeRadioList($settingForm, 'months', FiasUpdateSettingForm::MONTHS, [
                                    'item' => function ($index, $label, $name, $checked, $value) {
                                        return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                                    },
                                ])?>
                            </div>
                            <div class="col-sm-3 text-right">
                                <?= Html::radio(Html::getInputName($settingForm, 'months'), $settingForm->months == 'select', [
                                    'value' => 'select',
                                    'label' => 'Выберите:',
                                ])?>
                            </div>
                            <div class="col-sm-4">
                                <?= Html::activeDropDownList($settingForm, 'selectedMonths', FiasUpdateSettingForm::SELECTED_MONTHS, [
                                    'multiple' => true,
                                    'size' => 10,
                                    'style' => 'width: 90px;',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="text-center">
                            <?= Html::activeLabel($settingForm, 'weekdays')?>
                        </div>
                        <div class="row">
                            <?php if ($settingForm->hasErrors('selectedWeekdays')): ?>
                                <div class="col-sm-12 has-error">
                                    <span class="help-block"><?= Html::error($settingForm, 'selectedWeekdays') ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="col-sm-5">
                                <?= Html::activeRadioList($settingForm, 'weekdays', FiasUpdateSettingForm::WEEKDAYS, [
                                    'item' => function ($index, $label, $name, $checked, $value) {
                                        return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                                    },
                                ])?>
                            </div>
                            <div class="col-sm-3 text-right">
                                <?= Html::radio(Html::getInputName($settingForm, 'weekdays'), $settingForm->weekdays == 'select', [
                                    'value' => 'select',
                                    'label' => 'Выберите:',
                                ])?>
                            </div>
                            <div class="col-sm-4">
                                <?= Html::activeDropDownList($settingForm, 'selectedWeekdays', FiasUpdateSettingForm::SELECTED_WEEKDAYS, [
                                    'multiple' => true,
                                    'size' => 10,
                                    'style' => 'width: 120px;',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>
    </div>
    <div class="ibox-footer">
        Следующий запуск:
        <?php foreach ($settingForm->nextRunDates as $nextRunDate): ?>
            <br><?= $nextRunDate ?>
        <?php endforeach; ?>
    </div>
</div>

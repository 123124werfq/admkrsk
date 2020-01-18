<?php

use backend\models\forms\FiasUpdateSettingForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $settingForm FiasUpdateSettingForm */
/* @var $searchModel backend\models\search\FiasUpdateHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История обновлений';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fias-update-history-index">
    <div class="ibox">
        <div class="ibox-title">
            <h3>Настройки обновления</h3>
        </div>
        <div class="ibox-content">
            <?php $form = ActiveForm::begin(); ?>

            <div class="row">
                <div class="col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="text-center">
                                <?= Html::activeLabel($settingForm, 'minutes')?>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <?= Html::activeRadioList($settingForm, 'minutes', FiasUpdateSettingForm::MINUTES, [
                                        'item' => function ($index, $label, $name, $checked, $value) {
                                            return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                                        },
                                    ])?>
                                </div>
                                <div class="col-sm-3 text-right">
                                    <?= Html::activeRadio($settingForm, 'minutes', [
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
                                <div class="col-sm-5">
                                    <?= Html::activeRadioList($settingForm, 'hours', FiasUpdateSettingForm::HOURS, [
                                        'item' => function ($index, $label, $name, $checked, $value) {
                                            return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                                        },
                                    ])?>
                                </div>
                                <div class="col-sm-3 text-right">
                                    <?= Html::activeRadio($settingForm, 'hours', [
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
                                <div class="col-sm-5">
                                    <?= Html::activeRadioList($settingForm, 'days', FiasUpdateSettingForm::DAYS, [
                                        'item' => function ($index, $label, $name, $checked, $value) {
                                            return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                                        },
                                    ])?>
                                </div>
                                <div class="col-sm-3 text-right">
                                    <?= Html::activeRadio($settingForm, 'days', [
                                        'value' => 'select',
                                        'label' => 'Выберите:',
                                    ])?>
                                </div>
                                <div class="col-sm-4">
                                    <?= Html::activeDropDownList($settingForm, 'selectedDays', range(1, 12), [
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
                                <div class="col-sm-5">
                                    <?= Html::activeRadioList($settingForm, 'months', FiasUpdateSettingForm::MONTHS, [
                                        'item' => function ($index, $label, $name, $checked, $value) {
                                            return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                                        },
                                    ])?>
                                </div>
                                <div class="col-sm-3 text-right">
                                    <?= Html::activeRadio($settingForm, 'months', [
                                        'value' => 'select',
                                        'label' => 'Выберите:',
                                    ])?>
                                </div>
                                <div class="col-sm-4">
                                    <?= Html::activeDropDownList($settingForm, 'selectedMonths', [
                                        1 => 'Январь',
                                        2 => 'Февраль',
                                        3 => 'Март',
                                        4 => 'Апрель',
                                        5 => 'Май',
                                        6 => 'Июнь',
                                        7 => 'Июль',
                                        8 => 'Август',
                                        9 => 'Сентябрь',
                                        10 => 'Октябрь',
                                        11 => 'Ноябрь',
                                        12 => 'Декабрь',
                                    ], [
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
                                <div class="col-sm-5">
                                    <?= Html::activeRadioList($settingForm, 'weekdays', FiasUpdateSettingForm::WEEKDAYS, [
                                        'item' => function ($index, $label, $name, $checked, $value) {
                                            return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                                        },
                                    ])?>
                                </div>
                                <div class="col-sm-3 text-right">
                                    <?= Html::activeRadio($settingForm, 'weekdays', [
                                        'value' => 'select',
                                        'label' => 'Выберите:',
                                    ])?>
                                </div>
                                <div class="col-sm-4">
                                    <?= Html::activeDropDownList($settingForm, 'selectedWeekdays', [
                                        1 => 'Понедельник',
                                        2 => 'Вторник',
                                        3 => 'Среда',
                                        4 => 'Четверг',
                                        5 => 'Пятница',
                                        6 => 'Суббота',
                                        0 => 'Воскресенье',
                                    ], [
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
    </div>

    <div class="ibox">
        <div class="ibox-content">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'created_at:datetime:Дата обновления',
                    'text:text:Сообщение',
                    'version:integer:Версия',
                    'file',

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>

        </div>
    </div>
</div>

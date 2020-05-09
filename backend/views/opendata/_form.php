<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use common\models\Opendata;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Opendata */
/* @var $form yii\widgets\ActiveForm */

$this->registerJs("$('#opendata-id_collection').change(function () {
    var id_collection = $(this).val();
    
    if (id_collection) {
        $.ajax({
            url: '" . Url::to(['/collection/columns']) . "',
            data: { id: id_collection },
            success: function(data) {
                if (data.results) {
                    $('#opendata-columns').html('');
                    $.each(data.results, function(id, name) {
                        $('#opendata-columns').append('<option value=\"' + id + '\" selected=\"selected\">' + name + '</option>');
                    });
                }
            }
        });
    }
})");
?>

<?php $form = ActiveForm::begin(); ?>
<?= $form->errorSummary($model) ?>
<?= $form->field($model, 'identifier')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'owner')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'urls')->widget(Select2::class, [
    'pluginOptions' => [
        'allowClear' => true,
        'tags' => true,
        'multiple' => true,
        'placeholder' => 'Начните ввод',
    ],
]) ?>

<?= $form->field($model, 'id_user')->widget(Select2::class, [
    'data' => $model->id_user ? ArrayHelper::map([$model->user], 'id', 'username') : [],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 1,
        'placeholder' => 'Начните ввод',
        'ajax' => [
            'url' => Url::toRoute(['/user/list']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
    ],
]) ?>

<?= $form->field($model, 'id_collection')->widget(Select2::class, [
    'data' => $model->id_collection ? ArrayHelper::map([$model->collection], 'id_collection', 'name') : [],
    'pluginOptions' => [
        'allowClear' => true,
        'minimumInputLength' => 1,
        'placeholder' => 'Начните ввод',
        'ajax' => [
            'url' => Url::toRoute(['/collection/list']),
            'dataType' => 'json',
            'data' => new JsExpression('function(params) { return {q:params.term}; }')
        ],
    ],
]) ?>

<?= $form->field($model, 'columns')->dropDownList($model->id_collection ? ArrayHelper::map($model->collection->columns, 'id_column', 'name') : [], ['multiple' => true]) ?>

<hr>

<h3><?= Html::activeLabel($model, 'schedule_settings')?></h3>

<div class="row">
    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="text-center">
                    <?= Html::activeLabel($model, 'minutes')?>
                </div>
                <div class="row">
                    <?php if ($model->hasErrors('selectedMinutes')): ?>
                        <div class="col-sm-12 has-error">
                            <span class="help-block"><?= Html::error($model, 'selectedMinutes') ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="col-sm-5">
                        <?= Html::activeRadioList($model, 'minutes', Opendata::MINUTES, [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                            },
                        ])?>
                    </div>
                    <div class="col-sm-3 text-right">
                        <?= Html::radio(Html::getInputName($model, 'minutes'), $model->minutes == 'select', [
                            'value' => 'select',
                            'label' => 'Выберите:',
                        ])?>
                    </div>
                    <div class="col-sm-4">
                        <?= Html::activeDropDownList($model, 'selectedMinutes', range(0, 59), [
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
                    <?= Html::activeLabel($model, 'hours')?>
                </div>
                <div class="row">
                    <?php if ($model->hasErrors('selectedHours')): ?>
                        <div class="col-sm-12 has-error">
                            <span class="help-block"><?= Html::error($model, 'selectedHours') ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="col-sm-5">
                        <?= Html::activeRadioList($model, 'hours', Opendata::HOURS, [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                            },
                        ])?>
                    </div>
                    <div class="col-sm-3 text-right">
                        <?= Html::radio(Html::getInputName($model, 'hours'), $model->hours == 'select', [
                            'value' => 'select',
                            'label' => 'Выберите:',
                        ])?>
                    </div>
                    <div class="col-sm-4">
                        <?= Html::activeDropDownList($model, 'selectedHours', range(0, 23), [
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
                    <?= Html::activeLabel($model, 'days')?>
                </div>
                <div class="row">
                    <?php if ($model->hasErrors('selectedDays')): ?>
                        <div class="col-sm-12 has-error">
                            <span class="help-block"><?= Html::error($model, 'selectedDays') ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="col-sm-5">
                        <?= Html::activeRadioList($model, 'days', Opendata::DAYS, [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                            },
                        ])?>
                    </div>
                    <div class="col-sm-3 text-right">
                        <?= Html::radio(Html::getInputName($model, 'days'), $model->days == 'select', [
                            'value' => 'select',
                            'label' => 'Выберите:',
                        ])?>
                    </div>
                    <div class="col-sm-4">
                        <?= Html::activeDropDownList($model, 'selectedDays', Opendata::SELECTED_DAYS, [
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
                    <?= Html::activeLabel($model, 'months')?>
                </div>
                <div class="row">
                    <?php if ($model->hasErrors('selectedMonths')): ?>
                        <div class="col-sm-12 has-error">
                            <span class="help-block"><?= Html::error($model, 'selectedMonths') ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="col-sm-5">
                        <?= Html::activeRadioList($model, 'months', Opendata::MONTHS, [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                            },
                        ])?>
                    </div>
                    <div class="col-sm-3 text-right">
                        <?= Html::radio(Html::getInputName($model, 'months'), $model->months == 'select', [
                            'value' => 'select',
                            'label' => 'Выберите:',
                        ])?>
                    </div>
                    <div class="col-sm-4">
                        <?= Html::activeDropDownList($model, 'selectedMonths', Opendata::SELECTED_MONTHS, [
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
                    <?= Html::activeLabel($model, 'weekdays')?>
                </div>
                <div class="row">
                    <?php if ($model->hasErrors('selectedWeekdays')): ?>
                        <div class="col-sm-12 has-error">
                            <span class="help-block"><?= Html::error($model, 'selectedWeekdays') ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="col-sm-5">
                        <?= Html::activeRadioList($model, 'weekdays', Opendata::WEEKDAYS, [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return "<label>" . Html::radio($name, $checked, ['value' => $value]) . " " . $label . "</label><br>";
                            },
                        ])?>
                    </div>
                    <div class="col-sm-3 text-right">
                        <?= Html::radio(Html::getInputName($model, 'weekdays'), $model->weekdays == 'select', [
                            'value' => 'select',
                            'label' => 'Выберите:',
                        ])?>
                    </div>
                    <div class="col-sm-4">
                        <?= Html::activeDropDownList($model, 'selectedWeekdays', Opendata::SELECTED_WEEKDAYS, [
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

Следующий запуск:
<?php foreach ($model->nextRunDates as $nextRunDate): ?>
    <br><?= $nextRunDate ?>
<?php endforeach; ?>

<?php if (Yii::$app->user->can('admin.opendata')): ?>

    <hr>

    <h3>Доступ</h3>

    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

    <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

<?php endif; ?>

<hr>

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

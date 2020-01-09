<?php

use common\models\Statistic;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\StatisticSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="statistic-search search-form">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'fieldConfig'=>[
            'template' => '{input}',
        ]
    ]); ?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'model')->widget(Select2::class, [
                'data' => ArrayHelper::map(Statistic::find()->select('model')->groupBy('model')->all(), 'model', function(Statistic $model) {
                        $model = $model->model ? new $model->model : null;
                        return $model ? $model::VERBOSE_NAME_PLURAL : null;
                    }),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => 'Модель',
                ],
            ]) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'year')->widget(Select2::class, [
                'data' => ArrayHelper::map(Statistic::find()->select('year')->where(['not', ['year' => null]])->groupBy('year')->orderBy(['year' => SORT_DESC])->all(), 'year', 'year'),
                'pluginOptions' => [
                    'allowClear' => true,
                    'placeholder' => 'Год',
                ],
            ]) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Сбросить', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

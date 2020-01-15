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
                    <?= $form->field($settingForm, 'schedule')->textInput() ?>
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

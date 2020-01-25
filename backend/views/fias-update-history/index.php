<?php

use backend\models\forms\FiasUpdateSettingForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $settingForm FiasUpdateSettingForm */
/* @var $searchModel backend\models\search\FiasUpdateHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История обновлений';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fias-update-history-index">
    <?= $this->render('_form_setting', ['settingForm' => $settingForm])?>

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
                ],
            ]); ?>

        </div>
    </div>
</div>

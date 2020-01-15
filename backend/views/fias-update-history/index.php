<?php

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FiasUpdateHistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'История обновлений';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fias-update-history-index">
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

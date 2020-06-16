<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FirmUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запросы на редактирование';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firm-user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id_record',
            'id_user',
            'state',
            'created_at',
            'created_by',
            //'updated_at',
            //'updated_by',
            //'deleted_at',
            //'deleted_by',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
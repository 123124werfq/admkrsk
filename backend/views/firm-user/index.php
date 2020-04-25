<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FirmUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Firm Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firm-user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Firm User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

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

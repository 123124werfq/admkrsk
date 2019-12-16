<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceAppealFormSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Service Appeal Forms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="service-appeal-form-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Service Appeal Form', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_appeal',
            'id_form',
            'id_record_firm',
            'id_record_category',
            'id_service',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

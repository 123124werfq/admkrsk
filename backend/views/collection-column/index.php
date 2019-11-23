<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Collection Columns';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-column-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Collection Column', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_column',
            'id_collection',
            'id_dictionary',
            'name',
            'type',
            //'show_column_admin',
            //'ord',
            //'variables:ntext',
            //'alias',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>

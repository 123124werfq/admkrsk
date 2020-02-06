<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\GalleryGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Группы галлерей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-group-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать группы галлерей', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            'name',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => ['class' => 'button-column']
            ],
        ],
        'tableOptions' => [
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover',
            'id' => 'grid',
        ]
    ]); ?>


</div>

<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Муниципальные услуги';
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
?>

<div class="service-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id_service',
            'reestr_number:text:Номер',
            'name',
            'rubric.name:text:Рубрики',
            'targets.count:text:Целей',
            //'keywords:ntext',
            //'addresses:ntext',
            //'result:ntext',
            //'client_type',
            //'client_category:ntext',
            //'duration:ntext',
            //'documents:ntext',
            //'price:ntext',
            //'appeal:ntext',
            //'legal_grounds:ntext',
            //'regulations:ntext',
            //'regulations_link:ntext',
            //'duration_order:ntext',
            //'availability:ntext',
            //'procedure_information:ntext',
            //'max_duration_queue:ntext',
            'old',
            'online',
            //'created_at',
            //'created_by',
            //'updated_at',
            //'updated_by',
            //'deleted_at',
            //'deleted_by',

            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['class'=>'button-column']
            ],
        ],
        'tableOptions'=>[
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover'
        ]
    ]); ?>


</div>

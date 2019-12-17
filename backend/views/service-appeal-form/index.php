<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceAppealFormSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Service Appeal Forms';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ibox">
    <div class="ibox-content">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id_appeal:text:#',
            'id_form',
            'id_record_firm',
            'id_record_category',
            'id_service',
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions'=>['class'=>'button-column']
            ],
        ],
        'tableOptions'=>[
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover'
        ],
    ]); ?>
    </div>
</div>
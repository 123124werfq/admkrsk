<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FirmUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Запросы на редактирование';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="ibox">
    <div class="ibox-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'label'=>'#',
                    'attribute'=>'id_record',
                ],
                [
                    'label'=>'Учреждение',
                    'attribute'=>'record.label',
                ],
                'user.username',
                [
                    'label'=>'Учреждение',
                    'attribute'=>'state',
                    'value'=>function($model){
                        return $model->stateLabel;
                    }
                ],

                'created_at:date:Создано',
                //'updated_at',
                //'updated_by',
                //'deleted_at',
                //'deleted_by',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'contentOptions' => ['class' => 'button-column']
                ],
            ],
            'tableOptions' => [
                    'emptyCell ' => '',
                    'class' => 'table table-striped ids-style valign-middle table-hover',
                    'id' => 'grid',
                ]
        ]); ?>
    </div>
</div>
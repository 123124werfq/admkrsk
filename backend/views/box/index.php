<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Группы';
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
?>
<div class="ibox">
    <div class="ibox-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'id_box',
                'name'
            ],
            'tableOptions'=>[
                        'emptyCell '=>'',
                        'class'=>'table table-striped ids-style valign-middle table-hover'
                    ]
        ]); ?>
    </div>
</div>
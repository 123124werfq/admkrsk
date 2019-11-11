<?php

use common\models\Faq;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\FaqSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
if (Yii::$app->user->can('admin.faq')) {
    $this->params['button-block'][] = Html::a('Добавить вопрос', ['create'], ['class' => 'btn btn-success']);
}
?>
<div class="faq-index">
    <div class="ibox">
        <div class="ibox-content">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    'id_faq',
                    [
                        'attribute' => 'id_faq_categories',
                        'value' => function (Faq $model) {
                            return implode(', ', ArrayHelper::map($model->categories, 'id_faq_category', 'title'));
                        },
                    ],
                    [
                        'attribute' => 'status',
                        'value' => function (Faq $model) {
                            return $model->statusName;
                        },
                    ],
                    'question:html',
                    //'answer:html',
                    //'created_at',
                    //'created_by',
                    //'updated_at',
                    //'updated_by',
                    //'deleted_at',
                    //'deleted_by',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' => ['class' => 'button-column'],
                    ],
                ],
                'tableOptions'=>[
                    'emptyCell' => '',
                    'class' => 'table table-striped ids-style valign-middle table-hover'
                ]
            ]); ?>

        </div>
    </div>
</div>

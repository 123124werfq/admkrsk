<?php

use yii\helpers\Html;
use yii\grid\GridView;

use common\models\User;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$archive = Yii::$app->request->get('archive');

$this->title = 'Эксперты';
$this->params['breadcrumbs'][] = $this->title;

/*
if (Yii::$app->user->can('admin.service')) {
    if ($archive)
        $this->params['action-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    else
        $this->params['action-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);

    $this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
}
*/
?>

<div class="service-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            'id_expert:integer:ID',
            [
                'label' => 'ФИО',
                'value' => function($model){
                    return $model->user->getUsername();
                }
            ],
            [
                'label' => 'Дата добавления',
                'value' => function($model){
                    return date("d-m-Y H:i", $model->created_at);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{dismiss}',
                'buttons' => [
                    'dismiss' => function($url, $model, $key) {
                        $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                        return Html::a($icon, $url, [
                            'title' => 'Исключить',
                            'aria-label' => 'Исключить',
                            'data-pjax' => '0',
                        ]);
                    },
                ],
                'contentOptions'=>['class'=>'button-column'],
            ],
        ],
        'tableOptions'=>[
            'emptyCell' => '',
            'class' => 'table table-striped ids-style valign-middle table-hover'
        ]
    ]); ?>


</div>

<div id="user_group-users" class="row form-group">
    <div class="col-md-1">
        <h3>Пользователи</h3>
    </div>
    <div class="col-md-6">
        <div class="row">
            <?= Html::beginForm(['/reserve/promote', 'id' => $model->id_expert], 'post', ['data-pjax' => '0', 'class' => 'form-inline']); ?>

            <div class="form-group col-md-9">
                <?= Select2::widget([
                    'model' => $expertForm,
                    'attribute' => 'id_user',
                    'data' => $expertForm->id_user ? ArrayHelper::map([User::findOne($expertForm->id_user)], 'id', function ($model) {
                        /* @var User $model */
                        return $model->getUsername();
                    }) : null,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'ajax' => [
                            'url' => Url::toRoute(['/user/list']),
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                    'options' => ['class' => 'col-md-9'],
                ]) ?>
            </div>

            <div class="col-md-3">
                <?= Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'style' => 'width: 100%; margin-top: 3px;']) ?>
            </div>

            <?= Html::endForm() ?>
        </div>
    </div>
</div>
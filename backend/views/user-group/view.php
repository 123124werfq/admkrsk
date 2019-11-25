<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\UserGroup */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('История', ['history', 'id' => $model->primaryKey], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->primaryKey], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->primaryKey], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        'method' => 'post',
    ],
]);
?>
<div class="user-group-view">
    <div class="ibox">
        <div class="ibox-content">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'id_user_group',
                    'name',
                ],
            ]) ?>

        </div>
    </div>

    <div class="row form-group">
        <div class="col-md-9">
            <h3>Пользователи</h3>
        </div>
        <div class="col-md-3 text-right">
            <?= Html::a('Добавить пользователя', ['add-user', 'id' => $model->primaryKey], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-content">

            <?= GridView::widget([
                'dataProvider' => new ActiveDataProvider([
                    'query' => $model->getUsers()->joinWith('adinfo'),
                    'sort' => ['defaultOrder' => ['auth_ad_user.name' => SORT_ASC]],
                ]),
                //'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id:integer:ID пользователя',
                    'auth_ad_user.name:string:ФИО',
                    'email:email',

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' => ['class' => 'button-column'],
                        'template' => '{revoke}',
                        'buttons' => [
                            'revoke' => function($url, $model, $key) {
                                /* @var User $model */
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                                return Html::a($icon, $url, [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'aria-label' => Yii::t('yii', 'Delete'),
                                    'data-pjax' => '0',
                                ]);
                            },
                        ],
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

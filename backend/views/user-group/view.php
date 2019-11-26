<?php

use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\UserGroup */
/* @var $userGroupForm \backend\models\forms\UserGroupForm */

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

    <?php Pjax::begin([
        'enablePushState' => false,
        'scrollTo' => '#user_group-users',
    ]); ?>

        <div id="user_group-users" class="row form-group">
            <div class="col-md-8">
                <h3>Пользователи</h3>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <?= Html::beginForm(['/user-group/assign', 'id' => $model->id_user_group], 'post', ['data-pjax' => '1', 'class' => 'form-inline']); ?>

                    <div class="form-group col-md-9">
                        <?= Select2::widget([
                            'model' => $userGroupForm,
                            'attribute' => 'id_user',
                            'data' => $userGroupForm->id_user ? ArrayHelper::map([User::findOne($userGroupForm->id_user)], 'id', function ($model) {
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

        <div class="ibox">
            <div class="ibox-content">

                <?= GridView::widget([
                    'dataProvider' => new ActiveDataProvider([
                        'query' => $model->getUsers()->joinWith('adinfo'),
                        'sort' => [
                            'attributes' => [
                                'name' => [
                                    'asc' => ['auth_ad_user.name' => SORT_ASC],
                                    'desc' => ['auth_ad_user.name' => SORT_DESC],
                                ],
                            ],
                            'defaultOrder' => ['name' => SORT_ASC],
                        ],
                    ]),
                    //'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id:integer:ID пользователя',
                        [
                            'attribute' => 'username',
                            'value' => function (User $model) {
                                return $model->getUsername();
                            },
                        ],
                        'email:email',

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'contentOptions' => ['class' => 'button-column'],
                            'template' => '{revoke}',
                            'buttons' => [
                                'revoke' => function($url, $model, $key) use ($userGroupForm) {
                                    /* @var User $model */
                                    $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]);
                                    return Html::a($icon, ['/user-group/revoke', 'id' => $userGroupForm->id_user_group], [
                                            'title' => Yii::t('yii', 'Delete'),
                                            'aria-label' => Yii::t('yii', 'Delete'),
                                            'data' => [
                                                'method' => 'post',
                                                'params' => [Html::getInputName($userGroupForm, 'id_user') => $model->id],
                                                'pjax' => '1',
                                            ],
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

    <?php Pjax::end(); ?>
</div>

<?php

use backend\modules\log\models\Log;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\log\models\search\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \yii\db\ActiveRecord */
/* @var $parentModel \yii\db\ActiveRecord */
/* @var $parent array */
$parentModel = !empty($parent) ? $model->{$parent['relation']} : null;

$this->title = 'История';
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $parentModel ? $parentModel->pageTitle : $model->pageTitle, 'url' => ['view', 'id' => $parentModel ? $parentModel->primaryKey : $model->primaryKey]];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('Назад', ['view', 'id' => $model->primaryKey], ['class' => 'btn btn-default']);
?>
<div class="collection-index">
    <div class="ibox">
        <div class="ibox-content">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'created_at',
                        'label' => 'Дата изменения',
                        'format' => 'datetime',
                    ],
                    [
                        'attribute' => 'created_by',
                        'label' => 'Изменил',
                        'format' => 'raw',
                        'value' => function (Log $model) {
                            return $model->user ? Html::a($model->user->username, ['/user/view', 'id' => $model->user->id]) : null;
                        }
                    ],
                    [
                        'attribute' => 'data',
                        'label' => 'Изменено атрибутов',
                        'value' => function (Log $model) {
                            return $model->countChanges;
                        }
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'contentOptions' => ['class' => 'button-column'],
                        'template' => '{' . ($parentModel ? $parent['log'] : 'log') . '} {' . ($parentModel ? $parent['restore'] : 'restore') . '}',
                        'buttons' => [
                            $parentModel ? $parent['log'] : 'log' => function ($url, $model, $key) {
                                $title = Yii::t('yii', 'View');
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-eye-open"]);
                                return Html::a($icon, $url, [
                                    'title' => $title,
                                    'aria-label' => $title,
                                    'data-pjax' => '0',
                                ]);
                            },
                            $parentModel ? $parent['restore'] : 'restore' => function ($url, $model, $key) {
                                $title = Yii::t('yii', 'Restore');
                                $icon = Html::tag('span', '', ['class' => "glyphicon glyphicon-paste"]);
                                return Html::a($icon, $url, [
                                    'title' => $title,
                                    'aria-label' => $title,
                                    'data-pjax' => '0',
                                    'data-confirm' => 'Вы действительно хотите восстановить эти данные?',
                                ]);
                            },
                        ],
                    ],
                ],
                'tableOptions'=>[
                    'emptyCell '=>'',
                    'class'=>'table table-striped ids-style valign-middle table-hover'
                ]
            ]); ?>

        </div>
    </div>
</div>

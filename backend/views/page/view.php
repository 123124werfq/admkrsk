<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Page */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->render('_head',['model'=>$model]);
?>
<div class="row">
    <div class="col-lg-9">
        <div class="tabs-container">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a class="nav-link" data-toggle="tab" href="#tab-1">Дочерние разделы</a></li>
                <li>
                    <?=Html::a('Шаблон', ['layout', 'id' => $model->id_page], ['class' => 'nav-link'])?>
                </li>
                <li>
                    <?=Html::a('Новости', ['news/index', 'id_page' => $model->id_page], ['class' => 'nav-link'])?>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <h2>
                            <span class="pull-right">
                            <?=Html::a('Добавить ссылку', ['menu-link/create', 'id_page' => $model->id_page], ['class' => 'btn btn-default'])?>
                            <?=Html::a('Добавить подраздел', ['create', 'id_parent' => $model->id_page], ['class' => 'btn btn-default'])?>
                            </span>
                            Меню раздела
                        </h2>

                        <?php
                            if (empty($model->menu))
                            {
                                echo GridView::widget([
                                    'dataProvider' => $submenu,
                                    'columns' => [
                                        'id_page',
                                        'title',
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'contentOptions'=>['class'=>'button-column'],
                                            'template' => '{hide} {view} {update} {delete}',
                                            'buttons' => [
                                                'hide' => function ($url, $model, $key) {
                                                    return Html::a('', ['/page/hide', 'id' => $model->id_page],['class' => 'glyphicon glyphicon-fa-circle']);
                                                },
                                            ],
                                        ],
                                    ],
                                    'tableOptions'=>[
                                        'emptyCell' => '',
                                        'class' => 'table table-striped ids-style valign-middle table-hover'
                                    ]
                                ]);
                            }
                            else
                            {
                                echo GridView::widget([
                                    'dataProvider' => $submenu,
                                    'columns' => [
                                        'id_link',
                                        'label',
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'contentOptions'=>['class'=>'button-column'],
                                            'template' => '{hide} {view} {update} {delete}',
                                            'buttons' => [
                                                'hide' => function ($url, $model, $key) {
                                                    return Html::a('', ['/menu-link/hide', 'id' => $model->id_link],['class' => 'glyphicon glyphicon-fa-circle']);
                                                },
                                            ],
                                        ],
                                    ],
                                    'tableOptions'=>[
                                        'emptyCell' => '',
                                        'class' => 'table table-striped ids-style valign-middle table-hover'
                                    ]
                                ]);
                            }
                        ?>
                    </div>
                </div>
                <div role="tabpanel" id="tab-2" class="tab-pane">
                    <div class="panel-body">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
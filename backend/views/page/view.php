<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Page */

$this->title = $model->pageTitle;

if (empty($model->partition))
    $this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
else
    $this->params['breadcrumbs'][] = ['label' => $model->partition->title, 'url' => ['partition','id'=>$model->partition->id_page]];

if (!empty($model->parent))
    $this->params['breadcrumbs'][] = ['label' => $model->parent->title, 'url' => ['view', 'id' => $model->id_parent]];

$this->params['breadcrumbs'][] = $this->title;

$this->render('_head',['model'=>$model]);
?>
<div class="row">
    <div class="col-lg-9">
        <div class="tabs-container">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active"><a class="nav-link" data-toggle="tab" href="#tab-1">Дочерние разделы</a></li>
                <li>
                    <?=Html::a('Шаблон', ['template', 'id' => $model->id_page], ['class' => 'nav-link'])?>
                </li>
                <li>
                    <?=Html::a('Шаблон раздела', ['layout', 'id' => $model->id_page], ['class' => 'nav-link'])?>
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
                            <?=Html::a('Добавить ссылку', ['create', 'id_parent' => $model->id_page,'is_link'=>1], ['class' => 'btn btn-default'])?>
                            <?=Html::a('Добавить подраздел', ['create', 'id_parent' => $model->id_page], ['class' => 'btn btn-default'])?>
                            </span>
                            Меню раздела
                        </h2>

                        <?php
                            echo GridView::widget([
                                'dataProvider' => $submenu,
                                'columns' => [
                                    'id_page',
                                    [
                                        'attribute' => 'title',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return $model->title.'<br><a target="_blank" href="'.$model->getUrl(true).'">'.$model->getUrl().'</a>';
                                        },
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'contentOptions'=>['class'=>'button-column'],
                                        'template' => '{hide} {view} {update} {delete}',
                                        'buttons' => [
                                            'hide' => function ($url, $model, $key) {
                                                return Html::a('', ['/page/hide', 'id' => $model->id_page],['class' => (!$model->hidemenu)?'fa fa-toggle-on':'fa fa-toggle-off']);
                                            },
                                        ],
                                    ],
                                ],
                                'tableOptions'=>[
                                    'emptyCell' => '',
                                    'class' => 'table table-striped ids-style valign-middle table-hover ordered',
                                    'data-order-url'=>'/page/order'
                                ]
                            ]);

                                /*echo GridView::widget([
                                    'dataProvider' => $submenu,
                                    'columns' => [
                                        'id_link',
                                        [
                                            'attribute' => 'title',
                                            'format' => 'html',
                                            'value' => function ($link) {
                                                return $link->label.'<br><a target="_blank" href="'.$link->getUrl(true).'">'.$link->getUrl(true).'</a>';
                                            },
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'contentOptions'=>['class'=>'button-column'],
                                            'template' => '{hide} {update} {delete}',
                                            'buttons' => [
                                                'hide' => function ($url, $link, $key) {
                                                    return Html::a('', ['/menu-link/hide', 'id' => $link->id_link],['class' => ($link->state)?'fa fa-toggle-on':'fa fa-toggle-off']);
                                                },
                                                'update' => function ($url, $link, $key) use ($model) {
                                                    if (empty($link->page) || $link->page->id_parent != $model->id_page)
                                                        return Html::a('', ['/menu-link/update', 'id' => $link->id_link],['class' => 'glyphicon glyphicon-pencil']);
                                                    else
                                                        return Html::a('', ['/page/update', 'id' => $link->id_page],['class' => 'glyphicon glyphicon-pencil']);
                                                },
                                                'delete' => function ($url, $link, $key) use ($model) {
                                                    if (empty($link->page) || $link->page->id_parent != $model->id_page)
                                                        return Html::a('', ['/menu-link/delete', 'id' => $link->id_link],['class' => 'glyphicon glyphicon-trash','data' => [
                                                                'confirm' => 'Вы уверены что хотите удалить цель?',
                                                                'method' => 'post',
                                                        ]]);
                                                    else
                                                        return Html::a('', ['/page/delete', 'id' => $link->id_page, 'redirect'=>$model->id_page],['class' => 'glyphicon glyphicon-trash','data' => [
                                                              'confirm' => 'Вы уверены что хотите удалить цель?',
                                                              'method' => 'post',
                                                        ]]);
                                                }
                                            ],
                                        ],
                                    ],
                                    'tableOptions'=>[
                                        'emptyCell' => '',
                                        'class' => 'table table-striped ids-style valign-middle table-hover ordered',
                                        'data-order-url'=>'/menu/order'
                                    ]
                                ]);
                            }*/
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
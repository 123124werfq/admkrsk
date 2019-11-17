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
    <div class="col-md-9">
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
                            <?=Html::a('Добавить', ['create', 'id_parent' => $model->id_page], ['class' => 'btn btn-default pull-right'])?>
                            Дочерние разделы
                        </h2>
                        <?= GridView::widget([
                            'dataProvider' => $dataProviderChilds,
                            'columns' => [
                                'id_page',
                                [
                                    'attribute' => 'title',
                                    'contentOptions'=>['width'=>'40%']
                                ],
                                [
                                    'attribute' => 'alias',
                                    'format' => 'html',
                                    'value' => function ($model) {
                                        return '<a target="_blank" href="'.$model->getUrl(true).'">'.$model->getUrl().'</a>';
                                    },
                                    'contentOptions'=>['width'=>'40%']
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'contentOptions'=>['class'=>'button-column']
                                ],
                            ],
                            'tableOptions'=>[
                                'emptyCell '=>'',
                                'class'=>'table table-striped ids-style valign-middle table-hover ordered'
                            ]
                        ]); ?>
                        <br/>

                        <h2>
                            <?=Html::a('Редактировать', ['menu/update', 'id_page' => $model->id_page], ['class' => 'btn btn-default pull-right'])?>
                            Дополнительные ссылки в правом меню
                        </h2>
                        <?= GridView::widget([
                            'dataProvider' => $dataProviderMenu,
                            //'filterModel' => $searchModel,
                            'columns' => [
                                'id_link:text:#',
                                [
                                    'attribute'=>'label',
                                    'contentOptions'=>['width'=>'40%']
                                ],
                                [
                                    'attribute' => 'url',
                                    'format' => 'html',
                                    'value' => function ($model) {
                                        $url = (!empty($model->id_page))?$model->page->getUrl(true):$model->url;

                                        return '<a target="_blank" href="'.$url.'">'.$url.'</a>';
                                    },
                                    'contentOptions'=>['width'=>'57%']
                                ],
                                /*[
                                    'class' => 'yii\grid\ActionColumn',
                                    'contentOptions'=>['class'=>'button-column']
                                ],*/
                            ],
                            'tableOptions'=>[
                                'emptyCell '=>'',
                                'class'=>'table table-striped ids-style valign-middle table-hover ordered'
                            ]
                        ]); ?>>
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
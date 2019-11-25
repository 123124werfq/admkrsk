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
                            Дочерние разделы
                        </h2>
                        <br/>
                        <table class="table table-hover table-striped table-valign-middle">
                            <thead>
                                <tr>
                                    <th width="10">
                                    </th>
                                    <th>
                                        Заголовок
                                    </th>
                                    <th>
                                        Ссылка
                                    </th>
                                    <th width="100">
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="sortablepage">
                                <?php foreach ($rightmenu as $key => $data){
                                    $url = $data->getUrl(true);
                                    ?>
                                    <tr data-id="<?=(isset($data->id_link))?$data->id_link:$data->id_page?>" data-model="<?=(isset($data->id_link))?'menu':'page'?>">
                                        <td>
                                            <?=(isset($data->id_link))?'<span class="glyphicon glyphicon-link"></span>':''?>
                                        </td>
                                        <td>
                                            <?=(!empty($data->title))?$data->title:$data->label?>
                                        </td>
                                        <td>
                                            <a target="_blank" href="<?=$url?>"><?=$url?></a>
                                        </td>
                                        <td>
                                            <?php if (isset($data->id_link))
                                            {
                                                echo Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['menu-link/update', 'id' => $data->id_link],[
                                                    'class'=>'btn btn-default'
                                                ]).' ';

                                                echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['menu-link/delete', 'id' => $data->id_link],[
                                                    'data' => [
                                                        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                                        'method' => 'post',
                                                    ],
                                                    'class'=>'btn btn-default'
                                                ]);
                                            }
                                            else 
                                            {
                                                echo Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $data->id_page],[
                                                    'class'=>'btn btn-default'
                                                ]).' ';

                                                echo Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $data->id_page],[
                                                    'data' => [
                                                        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                                                        'method' => 'post',
                                                    ],
                                                    'class'=>'btn btn-default'
                                                ]);
                                            } 
                                            ?>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                        <br/>
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
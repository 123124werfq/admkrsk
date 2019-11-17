<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $page->title;
$this->params['breadcrumbs'][] = $this->title;
if (Yii::$app->user->can('admin.news')) {
    $this->params['button-block'][] = Html::a('Добавить новость', ['create','id_page'=>Yii::$app->request->get('id_page',0)], ['class' => 'btn btn-success']);
}
?>

<div class="row">
    <div class="tabs-container">
        <ul class="nav nav-tabs" role="tablist">
            <li><a class="nav-link" data-toggle="tab" href="#tab-1">Дочерние разделы</a></li>
            <li>
                <?=Html::a('Шаблон', ['layout', 'id' => $page->id_page], ['class' => 'nav-link'])?>
            </li>
            <li class="active">
                <?=Html::a('Новости', ['news/index', 'id_page' => $page->id_page], ['class' => 'nav-link'])?>
            </li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'columns' => [
                            'id_news',
                            'title',
                            'rub.lineValue:text:Рубрика',
                            'date_publish:date:Опубликовано',
                            'date_unpublish:date:Снять',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'contentOptions'=>['class'=>'button-column']
                            ],
                        ],
                        'tableOptions'=>[
                            'emptyCell '=>'',
                            'class'=>'table table-striped ids-style valign-middle table-hover'
                        ],
                        'formatter' => [
                            'class' => 'yii\i18n\Formatter',
                            'locale' => 'ru-RU',
                            'nullDisplay' => '',
                        ],

                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>

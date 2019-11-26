<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model common\models\Collection */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->primaryKey;

$this->params['button-block'][] = Html::a('Добавить', ['create', 'id' => $model->id_collection], ['class' => 'btn btn-success create-collection','data-toggle'=>"modal",'data-target'=>"#CollectionRecord"]);

$this->params['action-block'][] = Html::a('Редактировать', ['collection/update', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Удалить', ['collection/delete', 'id' => $model->id_collection],[
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        'method' => 'post',
    ],
]);
$this->params['action-block'][] = Html::a('История', ['collection/history', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Создать представление', ['collection/create-view', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Создать копию', ['/collection/copy', 'id' => $model->id_collection]);

?>
<div class="collection-view">
    <div class="ibox">
        <div class="ibox-content">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns'=>$columns,
                    'tableOptions'=>[
                        'emptyCell '=>'',
                        'class'=>'table table-striped valign-middle table-hover ids-style'
                    ]
                ]); ?>
            </div>
        </div>
    </div>
</div>

<div id="CollectionRecord" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Сохранить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

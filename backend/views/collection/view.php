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

$this->params['button-block'][] = Html::a('Добавить', ['record', 'id' => $model->id_collection], ['class' => 'btn btn-success create-collection','data-toggle'=>"modal",'data-target'=>"#CollectionRecord"]);

$this->params['action-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_collection],[
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
        'method' => 'post',
    ],
]);
$this->params['action-block'][] = Html::a('История', ['history', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Создать представление', ['create-view', 'id' => $model->id_collection]);

$this->params['action-block'][] = Html::a('Создать копию', ['copy', 'id' => $model->id_collection]);

?>
<div class="collection-view">
    <div class="ibox">
        <div class="ibox-content">
            <div class="table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'columns'=>$columns,
                    'tableOptions'=>[
                        'emptyCell '=>'',
                        'class'=>'table table-striped valign-middle table-hover'
                    ]
                ]); ?>
                <!--table class="table table-hover">
                    <?php /*
                        $records = $model->getData();
                        echo "<thead><tr><th></th>";
                        foreach ($model->columns as $ckey => $column) {
                            echo "<th>".$column->name."</th>";
                        }
                        $columns = $model->getColumns()->indexBy('id_column')->all();
                        echo '</tr></thead>
                        <tbody id="collection-rows">';
                        foreach ($records as $id_record => $row) {
                            echo $this->render("_record",['model'=>$model,'row'=>$row,'columns'=>$columns,'id_record'=>$id_record]);
                        }*/?>
                    </tbody>
                </table-->
            </div>
        </div>
    </div>
</div>

<div id="CollectionRecord" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <?php $form = ActiveForm::begin([
        'id'=>'CollectionRecordForm',
        'action'=>'/collection/record?id='.$model->id_collection
    ]);?>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    <?php ActiveForm::end(); ?>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

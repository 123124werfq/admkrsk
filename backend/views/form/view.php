<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Form */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Формы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = '<a class="btn btn-default" href="'.Yii::$app->params['frontendUrl'].'/form/view?id='.$model->id_form.'">Предпросмотр</a>';


$this->params['action-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_form]);

$this->params['action-block'][] = Html::a('Сделать копию', ['copy', 'id' => $model->id_form],[
        'data' => [
            'confirm' => 'Вы уверены что хотите сделать копию формы?',
            'method' => 'post',
        ],
    ]);

if ($model->isDeleted()) {
    $this->params['action-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->id_form]);
} else {
    $this->params['action-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_form], [
        'data' => [
            'confirm' => 'Вы уверены что хотите удалить форму?',
            'method' => 'post',
        ],
    ]);
}
?>
<div class="tabs-container">
    <ul class="nav nav-tabs" role="tablist">
        <li>
            <?=Html::a('Данные', ['collection-record/index', 'id' => $model->id_collection], ['class' => 'nav-link'])?>
        </li>
        <li>
            <?=Html::a('Колонки', ['collection-column/index', 'id' => $model->id_collection], ['class' => 'nav-link'])?>
        </li>
        <li class="active">
            <?=Html::a('Форма', ['form/view', 'id' => $model->id_form], ['class' => 'nav-link'])?>
        </li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active">
        <div id="form-template" class="panel-body">
    			  <?=$this->render('_form_view',['rows'=>$rows])?>
            <center>
                <a class="btn btn-default" href="create-row?id_form=<?=$model->id_form?>">Добавить строку</a>
            </center>
    		</div>
    	</div>
    </div>
</div>
<div id="FormElement" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>
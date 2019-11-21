<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Form */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Формы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->params['button-block'][] = '<a class="btn btn-default" href="'.Yii::$app->params['frontendUrl'].'/form/view?id='.$model->id_form.'">Предпросмотр</a>';
$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_form], ['class' => 'btn btn-primary']);
$this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_form], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены что хотите удалить форму?',
        'method' => 'post',
    ],
]);
?>
<div class="row" id="form-template">
    <div class="col-sm-8 col-sm-offset-2">
    	<div class="ibox">
    		<div class="ibox-title">
    			Шаблон формы
    		</div>
    		<div class="ibox-content">
    			<?php foreach ($rows as $key => $row){?>
    				<div class="form-row flex-row" data-id="<?=$row->id_row?>">
    					<?php foreach ($row->elements as $ikey => $element) {?>
							<?php
                                if (!empty($element->id_input))
                                    echo $this->render('_input',['element'=>$element]);
                                else if (!empty($element->content))
                                    echo $this->render('_element',['element'=>$element]);
                            ?>
    					<?php }?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-plus-circle"></i>
                                <!--span class="caret"></span-->
                            </button>
                            <ul class="dropdown-menu">
                              <li><a href="/form-input/create?id_row=<?=$row->id_row?>" class="create-form-input">Добавить поле</a></li>
                              <li><a href="/form-element/create?id_row=<?=$row->id_row?>" class="create-element">Добавить текст</a></li>
                              <li><a href="assign-form?id_row=<?=$row->id_row?>" class="create-form-text">Добавить подформу</a></li>
                            </ul>
                        </div>
    				</div>
    			<?php }?>
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
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

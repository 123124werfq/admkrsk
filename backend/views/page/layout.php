<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model common\models\Page */

$this->title = 'Шаблон раздела:' . $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_page]];
$this->params['breadcrumbs'][] = 'Шаблон';

?>

<div class="row">
    <div class="col-md-8">
        <div class="tabs-container">
            <ul class="nav nav-tabs" role="tablist">
                <li><?=Html::a('Информация', ['view', 'id' => $model->id_page], ['class' => 'nav-link'])?></li>
                <li class="active">
                    <?=Html::a('Шаблон', ['layout', 'id' => $model->id_page], ['class' => 'nav-link'])?>
                </li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <?php $form = ActiveForm::begin([
                        ]); ?>
                        <div class="row">
                            <div class="col-md-8">
                                <?=$form->field($block, 'type', ['template' => "{input}"])->dropDownList($block->getTypesLabels()) ?>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Добавить блок</button>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>

                        <div id="blocks" class="blocks" data-order-url="/block/order">
                            <?php foreach ($blocks as $key => $block) {?>
                                <?=$this->render('/block/_view',['data'=>$block])?>
                            <?php }?>
                        </div>
                        <center><a id="saveOrd" class="btn btn-success" style="display: none;" href="/block/order">Сохранить порядок</a></center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
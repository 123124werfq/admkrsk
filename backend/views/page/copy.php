<?php

use backend\models\forms\CopyPageForm;
use common\models\Page;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model Page */
/* @var $copyForm CopyPageForm */

$this->render('/page/_head',['model'=>$model]);

$this->title = 'Копировать раздел: ' . $model->pageTitle;
if (empty($model->partition))
    $this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
else 
    $this->params['breadcrumbs'][] = ['label' => $model->partition->title, 'url' => ['partition','id'=>$model->partition->id_page]];
$this->params['breadcrumbs'][] = ['label' => $model->pageTitle, 'url' => ['view', 'id' => $model->id_page]];
$this->params['breadcrumbs'][] = 'Копирование';
?>
<div class="page-copy">
    <div class="ibox">
        <div class="ibox-content">

            <?php $form = ActiveForm::begin(); ?>

            <?= $form->errorSummary($copyForm); echo $copyForm->id_parent; ?>

            <?= $form->field($copyForm, 'id_parent')->widget(Select2::class, [
                'data' => $copyForm->id_parent ? ArrayHelper::map([Page::findOne($copyForm->id_parent)], 'id_page', 'title') : [],
                'pluginOptions' => [
                    'multiple' => false,
                    'allowClear' => true,
                    'minimumInputLength' => 2,
                    'placeholder' => 'Начните ввод',
                    'ajax' => [
                        'url' => '/page/list',
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                ],
                'options'=>[
                    'prompt'=>'Выберите родителя'
                ]
            ]) ?>

            <hr>

            <?= Html::submitButton('Копировать', ['class' => 'btn btn-success']) ?>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>

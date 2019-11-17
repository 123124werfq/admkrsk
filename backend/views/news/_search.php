<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Collection;
/* @var $this yii\web\View */
/* @var $model backend\models\search\NewsSearch */
/* @var $form yii\widgets\ActiveForm */

$rubs = Collection::find()->where(['alias'=>"news_rubs"])->one();
$rubs = (!empty($rubs))?$rubs->getArray():[];

?>

<div class="news-search search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'fieldConfig'=>[
            'template' => '{input}',
        ]
    ]); ?>

    <?= Html::hiddenInput('id_page',$model->id_page)?>

    <div class="row">
        <div class="col-sm-3">
            <?= $form->field($model, 'title')->textInput(['placeholder'=>'Заголовок']) ?>
        </div>
        <div class="col-sm-3">
            <?= $form->field($model, 'id_rub')->dropDownList($rubs,['prompt'=>'Выбрите рубрику']) ?>
        </div>
        <div class="col-sm-2">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Сбросить',['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

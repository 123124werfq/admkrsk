<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

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
        <div class="col-sm-3">
            <?= $form->field($model, 'id_page')->dropDownList(ArrayHelper::map($news_pages, 'id_page', 'title'),['prompt'=>'Выбрите раздел']) ?>
        </div>

        <div class="col-sm-2">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Сбросить',['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

        <div>
            <a style="color: white" href="<?= Url::to(['', 'pageSize' => 10]) ?>"><button class="btn btn-primary">10</button></a>
            <a style="color: white" href="<?= Url::to(['', 'pageSize' => 20]) ?>"><button class="btn btn-primary">20</button></a>
            <a style="color: white" href="<?= Url::to(['', 'pageSize' => 40]) ?>"><button class="btn btn-primary">40</button></a>
        </div>

</div>

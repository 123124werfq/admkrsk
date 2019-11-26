<?php

use backend\widgets\UserAccessControl;
use backend\widgets\UserGroupAccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Page;
use common\models\Collection;
use kartik\select2\Select2;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */


$rubs = Collection::find()->where(['alias'=>"news_rubs"])->one();
$rubs = (!empty($rubs))?$rubs->getArray():[];

?>

<div class="row">
    <div class="col-sm-9">
        <div class="ibox">
            <div class="ibox-content">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'id_page')->widget(Select2::class, [
                    'data' => $model->id_page ? [$model->id_page=>$model->page->title]:[],
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
                ]) ?>
                <?=$form->field($model, 'id_rub')->widget(Select2::class, [
                    'data' => $rubs,
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => 'Выберите рубрику',
                    ],
                ])?>

                <?= $form->field($model, 'title')->textarea(['maxlength' => 255]) ?>
                <?= $form->field($model, 'description')->textarea(['maxlength' => 255]) ?>
                <?= $form->field($model, 'content')->textarea(['rows' => 6,'class'=>'redactor']) ?>

                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'date_publish')->textInput(['type'=>'datetime-local','value'=>(!empty($model->date_publish))?date('Y-m-d\TH:i:s', $model->date_publish):'']) ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'date_unpublish')->textInput(['type'=>'datetime-local','value'=>(!empty($model->date_unpublish))?date('Y-m-d\TH:i:s',$model->date_unpublish):'']) ?>
                    </div>
                </div>

                <?= $form->field($model, 'tagNames')->widget(Select2::class, [
                    'data' => $model->tagNames,
                    'pluginOptions' => [
                        'tags' => true,
                        'single'=>false,
                        'allowClear' => true,
                        'minimumInputLength' => 1,
                        'tokenSeparators' => [',', ' '],
                        'placeholder' => 'Введите теги',
                        'ajax' => [
                            'url' => '/tag/list',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                    'options' => [
                        'multiple' => true,
                    ],
                ]) ?>

                <?=common\components\multifile\MultiFileWidget::widget([
                    'model'=>$model,
                    'single'=>true,
                    'relation'=>'media',
                    'extensions'=>['jpg','jpeg','gif','png'],
                    'grouptype'=>1,
                    'showPreview'=>true
                ]);?>

                <?= $form->field($model, 'state')->dropDownList([0=>'Не активно',1=>'Активно']) ?>

                <?= $form->field($model, 'id_user')->widget(Select2::class, [
                    'data' => $model->id_user ? [$model->id_user=>$model->author->fullname]:[],
                    'pluginOptions' => [
                        'multiple' => false,
                        'allowClear' => true,
                        'minimumInputLength' => 2,
                        'placeholder' => 'Начните ввод',
                        'ajax' => [
                            'url' => '/user/list',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term}; }')
                        ],
                    ],
                ]) ?>

                <?= $form->field($model, 'main')->checkBox() ?>

                <?=$form->field($model, 'pages')->widget(Select2::class, [
                    'data' => ArrayHelper::map(\common\models\Page::find()->where('id_page IN (SELECT id_page FROM db_news)')->all(), 'id_page', 'title'),
                    'pluginOptions' => [
                        'allowClear' => true,
                        'placeholder' => 'Выберите раздел',
                        'multiple'=>true,
                    ],
                    'options'=>[
                        'multiple'=>true,
                    ]
                ])?>

                <hr>

                <?php if (Yii::$app->user->can('admin.news')): ?>

                    <hr>

                    <h3>Доступ</h3>

                    <?= $form->field($model, 'access_user_ids')->label('Пользователи')->widget(UserAccessControl::class) ?>

                    <?= $form->field($model, 'access_user_group_ids')->label('Группы пользоватей')->widget(UserGroupAccessControl::class) ?>

                <?php endif; ?>

                <hr>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
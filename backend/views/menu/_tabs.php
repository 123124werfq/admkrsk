<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\helpers\ArrayHelper;
    use common\models\Page;
?>
<div class="tabs-container">
    <div class="tab-content">
        <?php foreach ($links as $key => $link) {?>
        <div role="tabpanel" id="tab-<?=$key?>" class="tab-pane <?=$key==0?'active':''?>">
            <div class="panel-body">
                <?php $form = ActiveForm::begin(
                    ['action'=>'/menu-link/update','id'=>$model->id_link]
                ); ?>
                <?=$form->field($model, 'id_page')->dropDownList(ArrayHelper::map(Page::find()->all(), 'id_page', 'title'),['class'=>"form-control redactor"])?>
                <?=$form->field($model, 'content')->textArea(['class'=>"form-control redactor"])?>
                <hr/>
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?php }?>
    </div>
</div>
<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\ArrayHelper;

    use common\models\Page;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="ibox">
    <div class="ibox-content">
        <div id="list-records" class="multiinput sortable">
            <?php
                $records = $model->getRecords('links');
            ?>
            <?php foreach ($records as $key => $data) {?>
            <div class="row" data-row="<?=$key?>">
                <div class="col-md-3">
                    <?=Html::hiddenInput("MenuLink[links][$key][id_link]",$data->id_link,['id'=>'MenuLink_id_link_'.$key]);?>
                    <?=Html::hiddenInput("MenuLink[links][$key][ord]",$data->ord,['id'=>'MenuLink_ord_'.$key]);?>
                    <?=Html::textInput("MenuLink[links][$key][label]",$data->label,['required'=>true,'class'=>'form-control','id'=>'MenuLink_name_'.$key,'placeholder'=>'Введите название']);?>
                </div>
                <div class="col-md-3">
                    <?=Html::textInput("MenuLink[links][$key][url]",$data->url,['class'=>'form-control','id'=>'MenuLink_href_'.$key,'placeholder'=>'Введите URL']);?>
                </div>
                <div class="col-md-3">
                    <?=Html::dropDownList("MenuLink[links][$key][id_page]",$data->id_page,ArrayHelper::map(Page::find()->all(), 'id_page', 'title'),['class'=>'form-control','id'=>'CollectionColumn_type'.$key,'prompt'=>'Выберите раздел']);?>
                </div>
                <div class="col-md-1">
                    <a class="close" href="#">&times;</a>
                </div>
            </div>
            <?php }?>
        </div>
        <a class="btn btn-default" onclick="return addInput('list-records')" href="#">Добавить еще</a>
        <?php ActiveForm::end(); ?>
    </div>
</div>
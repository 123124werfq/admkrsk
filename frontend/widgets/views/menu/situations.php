<?php
    use yii\helpers\Html;

    if (empty($situations))
        $situations = common\models\ServiceSituation::find()->with('childs')->where('id_parent IS NULL')->all();
?>
<div class="situations">
    <?php foreach ($situations as $key => $data) {?>
    <div class="situations_item">
        <div class="situations_img">
            <img class="situations_img-picture" src="<?=(!empty($data->id_media))?$data->makeThumb(['w'=>64,'h'=>70]):''?>" alt="" width="64" height="70" alt="Название ситуациии">
        </div>
        <div class="situations_content">
            <h3 class="situations_title"><?=Html::encode($data->name)?></h3>
            <?php
                $datas = [];
                foreach ($data->childs as $ckey => $child)
                    $datas[] = '<a href="'.$child->getUrl().'">'.$child->name.'</a>';

                if (!empty($datas))
                {
            ?>
            <div class="situations_another">
                <?=implode('',$datas)?>
            </div>
            <?php }?>
        </div>
    </div>
    <?php }?>
</div>
<button class="load-more-block btn btn__primary btn__block show-hidden" data-show-target="#hidden-situations">Показать еще</button>
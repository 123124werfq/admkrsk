<?php
    use yii\helpers\Html;
?>
<div class="directions">
<?php foreach ($menu->links as $key => $link) {?>
    <div class="lk_item">
        <div class="lk_img">
            <img class="directions_img-picture" src="<?=(!empty($link->id_media))?$link->makeThumb(['w'=>64,'h'=>70]):''?>" alt="" width="64" height="70" alt="<?=Html::encode($link->label)?>">
        </div>
        <a href="<?=$link->getUrl()?>" class="directions_content">
            <h4 class="directions_title"><?=Html::encode($link->label)?></h4>
        </a>
    </div>
<?php }?>
</div>
<?php
    use yii\helpers\Html;
?>
<div class="regions clearfix">
  <?php foreach ($menu->activeLinks as $key => $link) {?>
  <div class="region-item">
      <div class="region-item-holder">
          <a href="<?=$link->getUrl()?>" class="region-item_main">
              <h3 class="region-item_title"><?=Html::encode($link->label)?></h3>
              <img class="region-item_img img-responsive" src="<?=(!empty($link->id_media))?$link->makeThumb(['w'=>768,'h'=>450]):''?>" alt="<?=Html::encode($link->label)?>">
          </a>
          <?php if (!empty($link->activeChilds)){?>
          <div class="region-item_menu">
            <?php foreach ($link->activeChilds as $ckey => $child) {?>
            <a class="region-item_menu-item" href="<?=$child->getUrl()?>"><?=$child->label?></a>
            <?php }?>
          </div>
          <?php }?>
      </div>
  </div>
  <?php }?>
</div>
<?php

use common\models\Gallery;
use common\models\Media;
use yii\helpers\Html;

/** @var  Gallery $gallery */
/** @var  Media[] $medias */

$cover = array_shift($medias);

if (empty($limit))
    $limit = 3;
?>
<div class="content-gallery">
    <div class="content-galery_main">
        <a href="<?= $cover->getUrl() ?>" data-fancybox="gallery-<?= $gallery->id_gallery ?>"data-caption="<?=Html::encode($cover->description.(!empty($cover->author)?' Автор: '.$cover->author:''))?>"><img
                    src="<?= $cover->showThumb(['w' => 768, 'h' => 450]) ?>" alt=""></a>
    </div>
    <div class="content-gallery_list">
        <?php foreach ($medias as $key => $media) {
            if ($key >= $limit)
                break;
            ?>
            <div class="content-gallery_item">
                <a href="<?= $media->getUrl() ?>" data-fancybox="gallery-<?= $gallery->id_gallery ?>" data-caption="<?=Html::encode($media->description.(!empty($media->author)?' Автор:'.$media->author:''))?>">
                    <img src="<?= $media->showThumb(['w' => 768, 'h' => 450]) ?>" alt="">
                    <?php if ($key >= ($limit - 1) && count($medias) >= ($limit + 1)) { ?>
                        <span class="content-gallery_count">+<?= count($medias) - $limit?></span>
                    <?php } ?>
                </a>
            </div>
        <?php } ?>
    </div>
    <div class="hidden">
        <?php
        foreach ($medias as $key => $media)
        {
            if ($key < $limit)
                continue;
            ?>
            <a href="<?= $media->showThumb(['w'=>1280]) ?>" data-fancybox="gallery-<?= $gallery->id_gallery ?>"></a>
        <?php } ?>
    </div>
</div>
<?php

use common\models\Gallery;
use common\models\Media;

/** @var  Gallery[] $galleries */
?>
<div class="content-gallery_list">
    <?php foreach ($galleries as $gallery): ?>
        <?php
            $medias = $gallery->medias;
            $firstMedia = array_shift($medias);
        ?>
        <div class="content-gallery_item content-gallery_item__single">
            <a href="<?=$firstMedia->showThumb(['w'=>1280])?>" data-fancybox="gallery-<?= $gallery->id_gallery ?>">
                <div class="content-gallery_img">
                    <img width="240" height="140" src="<?= $firstMedia->showThumb(['w' => 768, 'h' => 450]) ?>" alt="">
                    <span class="content-gallery_count">+ <?= count($medias) ?></span>
                </div>
                <?php if (!empty($medias)){?>
                <p class="content-gallery_item-title"> <?= $gallery->name ?></p>
                <?php }?>
            </a>
            <div class="hidden">
                <?php foreach ($medias as $key => $media): ?>
                    <a href="<?= $media->showThumb(['w'=>1280]) ?>" data-fancybox="gallery-<?= $gallery->id_gallery ?>"></a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
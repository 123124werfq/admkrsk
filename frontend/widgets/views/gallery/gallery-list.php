<?php

use common\models\Gallery;
use common\models\Media;

/** @var  Gallery[] $galleries */
if (empty($limit)) {
    $limit = 3;
}

?>
<div class="content-gallery_list">
    <?php foreach ($galleries as $gallery): ?>
        <?php
        /** @var Media $firstMedia */
        $firstMedia = $gallery->medias[0];
        unset($gallery->medias[0]);
        $limit = count($gallery->medias) <= $limit ? count($gallery->medias) : $limit;
        ?>
        <div class="content-gallery_item content-gallery_item__single">
            <a href="<?= $firstMedia->getUrl() ?>" data-fancybox="gallery-<?= $gallery->id_gallery ?>">
                <div class="content-gallery_img">
                    <img src="<?= $firstMedia->showThumb(['w' => 768, 'h' => 450]) ?>" alt="">
                    <!-- количество оставшихся фото в галерее-->
                    <span class="content-gallery_count"><?= $limit ?></span>
                </div>
                <p class="content-gallery_item-title"><?= $gallery->name ?></p>
            </a>
            <div class="hidden">
                <?php foreach ($gallery->medias as $key => $media): ?>
                    <?php if ($key >= $limit) break; ?>
                    <a href="<?= $media->getUrl() ?>" data-fancybox="gallery-<?= $gallery->id_gallery ?>">
                        <img src="<?= $media->showThumb(['w' => 768, 'h' => 450]) ?>" alt="">
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
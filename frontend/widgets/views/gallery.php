<?php 
	$cover = array_shift($medias);
	if (empty($limit))
		$limit = 3;
?>
<div class="content-gallery">
	<div class="content-galery_main">
		<a href="<?=$cover->getUrl()?>" data-fancybox="gallery-<?=$gallery->id_gallery?>"><img src="<?=$cover->showThumb(['w'=>768,'h'=>450])?>" alt=""></a>
	</div>
	<div class="content-gallery_list">
		<?php foreach ($medias as $key => $media) {
			if ($key>=$limit)
				continue;
		?>
		<div class="content-gallery_item">
			<a href="<?=$media->getUrl()?>" data-fancybox="gallery-<?=$gallery->id_gallery?>">
				<img src="<?=$media->showThumb(['w'=>768,'h'=>450])?>" alt="">
				<?php if ($key>=($limit-1) && count($medias)>=($limit+1)){?>
				<span class="content-gallery_count">+<?=count($medias)-$limit-1?></span>
				<?php }?>
			</a>
		</div>
		<?php }?>
	</div>
	<div class="hidden">
		<?php 
		foreach ($medias as $key => $media) {
			if ($key<$limit)
				continue;
		?>
		<a href="<?=$media->getUrl()?>" data-fancybox="gallery-<?=$gallery->id_gallery?>"><img src="<?=$media->showThumb(['w'=>768,'h'=>450])?>" alt=""></a>
		<?php }?>
	</div>
</div>
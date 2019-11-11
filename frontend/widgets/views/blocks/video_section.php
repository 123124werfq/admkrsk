<?php
    $cover = (!empty($blockVars['cover']))?$blockVars['cover']->makeThumb(['w'=>1920,'h'=>1080]):'';
?>
<div class="video-section">
	<a data-fancybox href="<?=(!empty($blockVars['youtube']))?$blockVars['youtube']->value:''?>">
    	<picture>
    		<source srcset="<?=$cover?>" media="(max-width: 767px)">
			<img class="video-section_cover" src="<?=$cover?>" alt="">
    	</picture>
    	<div class="video-section_desc">
    		<div class="container">
    			<h5 class="video-section_subtitle">Видео</h5>
    			<h4 class="video-section_title">
                    <?=(!empty($blockVars['name']))?$blockVars['name']->value:''?>
                </h4>
    		</div>
    	</div>
	</a>
</div>
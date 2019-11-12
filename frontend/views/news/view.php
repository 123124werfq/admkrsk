<?php
	use yii\helpers\Html;
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third">
                <h1 class="searchable"><?=Html::encode($model->title)?></h1>
                <?php if (!empty($model->tags)){?>
                <ul class="press_info">
                    <li class="press_info-item">
                    	<ul class="tags">
                            <?php foreach ($model->tags as $key => $tag) {
                                echo '<li class="tags-item"><a href="?tag='.$tag->name.'" class="tags-item_link">'.$tag->name.'</a></li>';
                            }?>
                    	</ul>
                    </li>
                    <li class="press_info-item"><?=strftime('%d %B %Y, %H:%M', $model->date_publish)?></li>
                </ul>
                <?php }?>
            </div>
            <div class="col-third">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
        <hr class="hr hr__large">
        <div class="row">
            <div class="col-2-third col-sm-12">
            	<div class="content searchable">
					<?php
                        preg_match_all ("/<(collection|gallery)\s(.+?)>(.+?)<\/(collection|gallery)>/is", $model->content, $matches);

                        if (!empty($matches[0]))
                            foreach ($matches[0] as $key => $match) {
                                $attributes = parseAttributesFromTag($match);

                                if (!empty($attributes['id']))
                                {
                                    $class = 'frontend\widgets\\'.ucwords($matches[1][$key]).'Widget';
                                    $model->content = str_replace($match, $class::widget(['attributes'=>$attributes]), $model->content);
                                }
                            }

                        echo $model->content;
                    ?>
				</div>
            </div>
        </div>
		<hr class="hr hr__md">
		<div class="row">
			<div class="col-2-third">
                <?php if (!empty($model->id_user)){?>
				<h3>Дополнительная информация для СМИ:</h3>
				<div class="person-card">
                    <?php if (!empty($model->author->id_media)){?>
					<img class="person-card_img" src="<?=$model->author->makeThumb(['w'=>160,'h'=>160])?>" alt="<?=$model->author->fullname?>">
                    <?php }?>
					<div class="person-card_content">
						<h4 class="person-card_title"><?=$model->author->fullname?></h4>
						<p class="person-card_subtitle">
							<?=nl2br($model->author->description)?>
						</p>
						<div class="person-card_contact">
                            <?php if (!empty($model->author->phone)){?>
							<a class="person-card_contact-item person-card_contact-item__phone" href="tel:<?=$model->author->phone?>"><?=$model->author->phone?></a>
                            <?php }?>
                            <?php if (!empty($model->author->email)){?>
							<a class="person-card_contact-item person-card_contact-item__email" href="mailto:info@domain.ru"><?=$model->author->email?></a>
                            <?php }?>
						</div>
					</div>
				</div>
                <?php }?>
                <?php if (!empty($model->tags)){?>
                <ul class="tags mb-3">
                    <?php foreach ($model->tags as $key => $tag) {
                        echo '<li class="tags-item"><a href="?tag='.$tag->name.'" class="tags-item_link">'.$tag->name.'</a></li>';
                    }?>
                </ul>
                <?php }?>
            	<p class="text-help">
                    Дата публикации: <span class="publish-date"><?=date('d.m.Y',$model->date_publish)?></span><br>
                    Просмотров за всего: <?=$model->views?>
                </p>
                <div class="subscribe">
                    <div class="subscribe_left">
                        Поделиться:
                        <div class="ya-share2 subscribe_share" data-services="vkontakte,facebook,odnoklassniki"></div>
                    </div>
                    <div class="subscribe_right"><a class="btn-link" onclick="print()"><i class="material-icons subscribe_print">print</i> Распечатать</a></div>
                </div>
			</div>
		</div>
    </div>
</div>

<?php if (!empty($similar_news)){?>
<div class="section-additional">
	<div class="container">
		<h2>Похожие новости</h2>
		<div class="events-list">
            <?php foreach ($similar_news as $key => $data) {?>
                <div class="events-item">
                    <?php if (!empty($data->id_media)){?>
                    <a href="<?=$data->getUrl()?>" class="events_img-holder">
                        <img class="events_img img-responsive" src="<?=$data->makeThumb(['w'=>768,'h'=>384])?>" alt="<?=Html::encode($data->name)?>">
                    </a>
                    <?php }?>
                    <h4 class="events_title"><a href="<?=$data->getUrl()?>"><?=Html::encode($data->title)?></a></h4>
                    <ul class="events_info">
                        <?php if (!empty($data->id_rub)){?>
                        <li class="press_info-item press_info-item__place"><a href="?id_rub=<?=$data->id_rub?>"><?=$data->rub->getLineValue()?></a></li>
                        <?php }?>
                        <li class="press_info-item"><?=strftime('%d %B %Y, %H:%M', $data->date_publish)?></li>
                    </ul>
                </div>
            <?php }?>
        </div>
	</div>
</div>
<?php }?>
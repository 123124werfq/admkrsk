<?php
/* @var common\models\News $model */

use yii\helpers\Html;
function parseAttributesFromTag($tag){
    $pattern = '/(\w+)=[\'"]([^\'"]*)/';

    preg_match_all($pattern,$tag,$matches,PREG_SET_ORDER);

    $result = [];
    foreach($matches as $match){
        $attrName = $match[1];
        $attrValue = is_numeric($match[2])? (int)$match[2]: trim($match[2]);
        $result[$attrName] = $attrValue;
    }

    return $result;
}
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third col-sm-12">
                <h1 class="searchable"><?=Html::encode($model->title)?></h1>
                <ul class="press_info">
                    <?php if (!empty($model->tags)){?>
                    <li class="press_info-item">
                    	<ul class="tags">
                            <?php foreach ($model->tags as $key => $tag) {
                                echo '<li class="tags-item"><a href="?tag='.$tag->name.'" class="tags-item_link">'.$tag->name.'</a></li>';
                            }?>
                    	</ul>
                    </li>
                    <?php }?>
                    <li class="press_info-item"><?=strftime('%d %B %Y, %H:%M', $model->date_publish)?></li>
                </ul>
                <!--hr class="hr hr__large"-->
            	<div class="content searchable">
					<?php
                        preg_match_all ("/<(collection|gallery|forms)\s(.+?)>(.+?)<\/(collection|gallery|forms)>/is", $model->content, $matches);

                        if (!empty($matches[0]))
                            foreach ($matches[0] as $key => $match)
                            {
                                $attributes = parseAttributesFromTag($match);

                                if (!empty($attributes['id']))
                                {
                                    $class = 'frontend\widgets\\'.ucwords($matches[1][$key]).'Widget';

                                    $model->content = str_replace($match, $class::widget(['attributes'=>$attributes,'page'=>$page]), $model->content);
                                }
                            }

                        echo $model->content;
                    ?>
				</div>
            </div>
            <div class="col-third">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
		<hr class="hr hr__md">
		<div class="row">
			<div class="col-2-third">
                <?=$this->render('_contact',['model'=>$model])?>
                <?php if (!empty($model->tags)){?>
                <ul class="tags mb-3">
                    <?php foreach ($model->tags as $key => $tag) {
                        echo '<li class="tags-item"><a href="?tag='.$tag->name.'" class="tags-item_link">'.$tag->name.'</a></li>';
                    }?>
                </ul>
                <?php }?>
            	<p class="text-help">
                    Дата публикации (изменения): <?=date('d.m.Y',$model->date_publish)?> (<?=date('d.m.Y',$model->updated_at)?>)<br>
                    Просмотров за год (всего): <?=$model->viewsYear?> (<?=$model->views?>)
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
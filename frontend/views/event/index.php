<?php
	$this->params['page'] = $page;
?>
<div class="main">
	<div class="container">
		<?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
		<div class="row">
		    <div class="col-2-third">
		        <h1><?=$page->title?></h1>
		        <div class="header-controls">
                    <form id="news-filter" action="" method="get">
                        <div class="btn-group">
                            <div class="btn-group_item">
                                <div class="datepicker-holder">
                                    <input name="date" id="news-date" value="<?=$date?>" type="text" class="form-control form-control_datepicker mb-sm-all-0 datepicker-ajax <?=!empty($date)?'datepicker__filled':''?>" placeholder="<?=Yii::t('site', 'Показать за период')?>">
                                    <button class="form-control-reset material-icons" type="button">clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
		        <div class="events-list">
	            <?php foreach ($projects as $key => $data) {?>
	                <div class="events-item">
	                    <a href="#" class="events_img-holder">
	                        <img class="events_img img-responsive" src="<?=$data->makeThumb(['w'=>768,'h'=>384])?>" alt="<?=$data->name?>">
	                    </a>
	                    <h4 class="events_title"><a href="<?=(!empty($data->id_page))?$data->page->getUrl():$data->url?>"><?=$data->name?></a></h4>
	                    <ul class="events_info">
	                        <?php if (!empty($data->typeValue)){?>
	                        <li class="events_info-item events_info-item__place"><a href="#"><?=$data->typeValue->getLineValue()?></a></li>
	                        <?php }?>
	                        <li class="events_info-item"><?=strftime('%d %B %Y',$data->date_begin)?> <?=(!empty($data->date_end))?' - '.strftime('%d %B %Y',$data->date_end):''?></li>
	                    </ul>
	                </div>
	            <?php }?>
	        	</div>
	        	<?php if (empty($projects)){?>
	            	<p>Нет событий и проектов за выбранный период</p>
	            <?php }?>
		    </div>
		    <div class="col-third">
		        <?=$this->render('/site/_rightmenu',['page'=>$page])?>
		    </div>
		</div>
	</div>
</div>
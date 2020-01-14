<?php
	use yii\helpers\Html;

	$this->params['page'] = $page;

	$date = Html::encode(Yii::$app->request->get('date'));
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
                                <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                    <select name="id_rub" id="news-rubric">
                                    	<option value="">Рубрика</option>
                                    	<option value="">Все рубрики</option>
                                    	<?php foreach ($rubrics as $key => $rub)
                                    		echo '<option value="'.$rub->id_record.'" '.($rub->id_record==$id_rub?'selected':'').'>'.$rub->getLineValue().'</option>';
                                    	?>
                                    </select>
                                </div>
                            </div>
                            <div class="btn-group_item">
                                <div class="datepicker-holder">
                                    <input name="date" id="news-date" value="<?=$date?>" type="text" class="form-control form-control_datepicker mb-sm-all-0 datepicker-ajax <?=!empty($date)?'datepicker__filled':''?>" placeholder="Показать новости за период">
                                    <button class="form-control-reset material-icons" type="button">clear</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
		        <div class="press-list">
		            <!--div class="press-item press-item__wide">
		                <div class="press_img-holder">
		                    <img class="press_img img-responsive" src="https://placekitten.com/768/480" alt="Название новости">
		                    <div class="press_content">
		                        <ul class="press_info hidden-xs hidden-accessability">
		                            <li class="press_info-item press_info-item__place"><a href="#">Событие</a></li>
		                            <li class="press_info-item">20 декабря 2019 — 31 января 2018</li>
		                        </ul>
		                        <h3 class="press_title"><a href="#">В Красноярске чествуют Петра Ивановича Пимашкова</a></h3>
		                        <ul class="press_info visible-xs">
		                            <li class="press_info-item press_info-item__place"><a href="#">Событие</a></li>
		                            <li class="press_info-item">20 декабря 2019 — 31 января 2018</li>
		                        </ul>
		                    </div>
		                </div>
		            </div-->
		            <?php foreach ($news as $key => $data) {
		            	echo $this->render('_news',['data'=>$data]);
		            }?>
		        </div>
		        <!-- на время загрузки аякса добавлять к load-more класс active для анимации -->
		        <?php if (count($news)<$totalCount){?>
		        <a href="" class="load-more" data-pagesize="20">
		            <span class="load-more_label">Показать ещё</span>
		            <span class="load-more_loader">
		                <span class="load-more_dot-1"></span>
		                <span class="load-more_dot-2"></span>
		                <span class="load-more_dot-3"></span>
		            </span>
		        </a>
		    	<?php }?>
		    </div>
		    <div class="col-third">
		        <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>

		        <!--h3 class="sidebar-title">Последние новости</h3>
		        <div class="side-news-list">
		            <div class="side-news-item">
		                <p class="side-news-item_date">16:43</p>
		                <a href="#" class="side-news-item_title">
		                    Член ЦИК России Александр Кинёв принял участие в заседании Избирательной комиссии Магаданской области в режиме видеоконференции
		                </a>
		            </div>
		            <div class="side-news-item">
		                <p class="side-news-item_date">16:43</p>
		                <a href="#" class="side-news-item_title">
		                    Член ЦИК России Александр Кинёв принял участие в заседании Избирательной комиссии Магаданской области в режиме видеоконференции
		                </a>
		            </div>
		            <div class="side-news-item">
		                <p class="side-news-item_date">16:43</p>
		                <a href="#" class="side-news-item_title">
		                    Член ЦИК России Александр Кинёв принял участие в заседании Избирательной комиссии Магаданской области в режиме видеоконференции
		                </a>
		            </div>
		            <div class="side-news-item">
		                <p class="side-news-item_date">16:43</p>
		                <a href="#" class="side-news-item_title">
		                    Член ЦИК России Александр Кинёв принял участие в заседании Избирательной комиссии Магаданской области в режиме видеоконференции
		                </a>
		            </div>
		        </div-->
		    </div>
		</div>
	</div>
</div>
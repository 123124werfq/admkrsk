
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div>
            <h1><?=$situation->name?></h1>

			<div class="smart-menu-tabs slide-hover tab-controls tab-controls__responsive">
                <div class="tab-controls-holder">
                    <span class="slide-hover-line"></span>
                    <div class="smart-menu-tabs_item tab-control tab-control__active slide-hover-item" data-href="#reestr"><a class="smart-menu-tabs_control">Реестр муниципальных услуг</a></div>
                    <div class="smart-menu-tabs_item tab-control slide-hover-item" data-href="#situations"><a class="smart-menu-tabs_control">Жизненные ситуации</a></div>
                </div>
            </div>

            <div class="smart-menu-content">
                <div id="reestr" class="tab-content active">
                    <div class="row">
                        <div class="col-2-third">
                            <div class="content">
                              <?=$this->render('_table',['services'=>$services])?>
                            </div>
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
                <div id="situations" class="tab-content">
                    <div class="svg-hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" width="0" height="0">
                        <filter id="svgred">
                                <feColorMatrix
                                  type="matrix"
                                  values="0 0 0 0 0.8
                                          0 0.16 0 0 0
                                          0 0 0.18 0 0
                                          0 0 0 1 0 "/>
                            </filter>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="0" height="0">
                            <filter id="border-red">
                                <feColorMatrix
                                  type="matrix"
                                  values="1 0 0 0 0
                                          0 0 0 0 0
                                          0 0 0 0 0
                                          0 0 0 1 0 "/>
                            </filter>
                        </svg>
                    </div>
                    <?=frontend\widgets\ServiceSituationWidget::widget()?>
                </div>
            </div>
        </div>
    </div>
</div>
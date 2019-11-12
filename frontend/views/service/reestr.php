
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div>
            <h1><?=$page->title?></h1>
            <!--div class="header-controls">
                <form>
                    <div class="btn-group">
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <select>
                                    <option selected="selected">Орган, оказывающий услугу</option>
                                    <option value="0">Любой орган</option>
                                    <option value="1">Орган 1</option>
                                    <option value="2">Орган 2</option>
                                    <option value="3">Орган 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <select>
                                    <option selected="selected">Категория получателей</option>
                                    <option value="0">Любая категория</option>
                                    <option value="1">Категория 1</option>
                                    <option value="2">Категория 2</option>
                                    <option value="3">Категория 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <select>
                                    <option selected="selected">Форма предоставления</option>
                                    <option value="0">Любая форма</option>
                                    <option value="1">Форма 1</option>
                                    <option value="2">Форма 2</option>
                                    <option value="3">Форма 3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div-->

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
                            <?php foreach ($rubrics as $key => $rub) {?>
							<h2 class="mt-0"><?=$rub->name?></h2>
							<div class="reestr">
                                <?php foreach ($rub->childs as $ckey => $child) {?>
    								<h3 <?=empty($child->childs)?'class="collapse-control"':''?>><?=$child->name?></h3>
                                    <?php foreach ($child->childs as $cskey => $subchild) {?>
        								<h4 class="fw-500 collapse-control"><?=$subchild->name?></h4>
        								<div class="collapse-content content">
                                            <div class="table-responsive">
            									<table class="label-table">
            										<tr>
            											<th>Реестровый номер услуги</th>
            											<th>Наименование услуги</th>
            										</tr>
                                                    <?php foreach ($subchild->services as $key => $service) {?>
                                                        <tr>
                                                            <td><?=$service->reestr_number?></td>
                                                            <td>
                                                                <h5><a href="<?=$service->getUrl()?>"><?=$service->fullname?></a></h5>
                                                            </td>
                                                        </tr>
                                                    <?php }?>
            									</table>
                                            </div>
        								</div>
                                    <?php }?>
                                    <?php if (empty($child->childs)){?>
                                        <div class="collapse-content content">
                                            <div class="table-responsive">
                                                <table class="label-table">
                                                    <tr>
                                                        <th>Реестровый номер услуги</th>
                                                        <th>Наименование услуги</th>
                                                    </tr>
                                                    <?php foreach ($child->services as $key => $service) {?>
                                                        <tr>
                                                            <td><?=$service->reestr_number?></td>
                                                            <td>
                                                                <h5><a href="<?=$service->getUrl()?>"><?=$service->fullname?></a></h5>
                                                            </td>
                                                        </tr>
                                                    <?php }?>
                                                </table>
                                            </div>
                                        </div>
    							     <?php }?>	
                                 <?php }?>
							</div>
                            <?php }?>

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
<?php
    $this->params['page'] = $page;
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div>
            <h1><?=$page->title?></h1>
            <div class="header-controls">
                <form id="reestr-filters" action="" method="get">
                    <div class="btn-group">
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <select name="firm">
                                    <option selected="selected">Орган, оказывающий услугу</option>
                                    <option value="0">Любой орган</option>
                                    <?php foreach ($firms as $id => $name) {
                                        echo '<option value="'.$id.'">'.$name.'</option>';
                                    }?>
                                </select>
                            </div>
                        </div>
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <select name="client_type">
                                    <option selected="selected">Категория получателей</option>
                                    <option value="0">Любая категория</option>
                                    <?php foreach (\common\models\Service::getAttributeValues('client_type') as $value => $label) {
                                        echo '<option value="'.$value.'">'.$label.'</option>';
                                    }?>
                                </select>
                            </div>
                        </div>
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <select name="online">
                                    <option selected="selected">Форма предоставления</option>
                                    <option value="0">Любая форма</option>
                                    <option value="1">в электронном виде</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

			<div class="smart-menu-tabs slide-hover tab-controls tab-controls__responsive">
                <div class="tab-controls-holder">
                    <span class="slide-hover-line"></span>
                    <div class="smart-menu-tabs_item tab-control tab-control__active slide-hover-item" data-href="#reestr"><a class="smart-menu-tabs_control">Реестр муниципальных услуг</a></div>
                    <div class="smart-menu-tabs_item tab-control slide-hover-item" data-href="#situations"><a class="smart-menu-tabs_control">Жизненные ситуации</a></div>
                </div>
            </div>

            <div class="smart-menu-content">
                <?=$this->render('_reestr',['page'=>$page,'rubrics'=>$rubrics,'servicesRubs'=>$servicesRubs,'active'=>($open)?'active':''])?>

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
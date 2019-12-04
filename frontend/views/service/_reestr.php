<div id="reestr" class="tab-content active">
    <div class="row">
        <div class="col-2-third">
            <?php
            foreach ($rubrics[0] as $key => $rub) {
                ?>
			<h2 class="mt-0"><?=$rub->name?></h2>
			<div class="reestr">
                <?php
                if (!empty($rubrics[$rub->id_rub]))
                    foreach ($rubrics[$rub->id_rub] as $ckey => $child) {?>
					<h3 <?=empty($rubrics[$child->id_rub])?'class="collapse-control '.$active.'"':''?>><?=$child->name?></h3>
                    
                    <?php 
                        if (!empty($rubrics[$child->id_rub]))
                            foreach ($rubrics[$child->id_rub] as $cskey => $subchild) {
                                if (!empty($servicesRubs[$subchild->id_rub])){?>
            						<h4 class="fw-500 collapse-control <?=$active?>"><?=$subchild->name?></h4>
            						<div class="collapse-content content" <?=$active?'style="display:block;"':''?>>
                                        <?=$this->render('_table',['services'=>$servicesRubs[$subchild->id_rub]])?>
            						</div>
                            <?php }
                            }?>

                    <?php if (empty($rubrics[$child->id_rub])){
                        if (!empty($servicesRubs[$child->id_rub])){?>
                        <div class="collapse-content content" <?=$active?'style="display:block;"':''?>>
                            <?=$this->render('_table',['services'=>$servicesRubs[$child->id_rub]])?>
                        </div>
				        <?php }
                        }?>
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
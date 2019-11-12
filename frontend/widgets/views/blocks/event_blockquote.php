<div class="intro">
	<div class="container">
		<div class="row">
			<div class="col-2-third">
				<h3 class="intro_title"><?=(!empty($blockVars['title']))?$blockVars['title']->value:''?></h3>
			</div>
		</div>
		<div class="row">
			<div class="col-2-third order-xs-1">
				<div class="intro_content content">
					<blockquote class="intro_decor-quote"></blockquote>
					<?=(!empty($blockVars['content']))?$blockVars['content']->value:''?>
				</div>
				<div class="subscribe">
					<div class="subscribe_left">
						Поделиться:
						<div class="ya-share2 subscribe_share" data-services="vkontakte,facebook,odnoklassniki"></div>
					</div>
					<div class="subscribe_right"><a class="btn-link" onclick="print()"><i class="material-icons subscribe_print">print</i> Распечатать</a></div>
				</div>
			</div>
			<div class="col-third person-col order-xs-0">
				<?php if (!empty($blockVars['user'])){
					$user = \common\models\User::findOne($blockVars['user']->value);

					if (!empty($user)){
				?>
				<div class="person-card person-card__mini">
					<?php if (!empty($user->id_media)){?>
                        <img class="person-card_img" src="<?=$user->makeThumb(['w'=>160,'h'=>160])?>" alt="<?=$user->fullname?>">
                    <?php }?>
					<div class="person-card_content">
						<h4 class="person-card_title"><?=$user->fullname?></h4>
						<p class="person-card_subtitle">
							<?=$user->description?>
						</p>
					</div>
				</div>
				<?php }}?>
			</div>
		</div>
	</div>
</div>
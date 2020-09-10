<?php if (!empty($model->contacts)){?>
	<h3><?=Yii::t('site', 'Дополнительная информация для СМИ')?>:</h3>
	<?php
	foreach ($model->contactsRecords as $key => $contact){

		$data = $contact->getData(true);
		$photo = $contact->getMedia('photo',true);
	?>
	<div class="person-card">
	    <?php if (!empty($photo)){?>
		<img class="person-card_img" src="<?=$photo->showThumb(['w'=>160,'h'=>160])?>" alt="<?=$data['name']??''?>">
	    <?php }?>
		<div class="person-card_content">
			<h4 class="person-card_title"><?=$data['name']??''?></h4>
			<p class="person-card_subtitle">
				<?=nl2br($data['description']??'description')?>
			</p>
			<div class="person-card_contact">
	            <?php if (!empty($data['phone'])){?>
				<a class="person-card_contact-item person-card_contact-item__phone" href="tel:<?=$data['phone']?>"><?=$data['phone']?></a>
	            <?php }?>
	            <?php if (!empty($data['mobile_phone'])){?>
				<a class="person-card_contact-item person-card_contact-item__phone" href="tel:<?=$data['mobile_phone']?>"><?=$data['mobile_phone']?></a>
	            <?php }?>
	            <?php if (!empty($data['email'])){?>
				<a class="person-card_contact-item person-card_contact-item__email" href="mailto:info@domain.ru"><?=$data['email']?></a>
	            <?php }?>
			</div>
		</div>
	</div>
	<?php }?>
<?php }?>
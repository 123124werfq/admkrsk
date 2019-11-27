<?php if (!empty($model->contact)){
	$data = $model->contact->getData(true);

	$photo = $model->contact->getMedia('photo',true);
?>
<h3>Дополнительная информация для СМИ:</h3>
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
            <?php if (!empty($data['email'])){?>
			<a class="person-card_contact-item person-card_contact-item__email" href="mailto:info@domain.ru"><?=$data['email']?></a>
            <?php }?>
		</div>
	</div>
</div>
<?php }?>
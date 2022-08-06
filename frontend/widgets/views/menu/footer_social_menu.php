<div class="footer-socials">
    <div class="footer-socials_holder">
        <?php foreach ($menu->activeLinks as $key => $data) {?>
        <a href="<?=$data->getUrl()?>" class="footer-socials_item">
            <img class="footer-socials_icon" src="<?=$data->makeThumb(['w'=>22,'h'=>22])?>" />
        </a>
        <?php }?>
    </div>
</div>
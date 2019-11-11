<ul class="footer-menu footer-menu__col">
    <?php foreach ($menu->links as $key => $data) {?>
        <li class="footer-menu_item">
            <a class="footer-menu_link" href="<?=($data->id_page)?$data->page->getUrl():$data->url?>"><?=$data->label?></a>
        </li>
    <?php }?>
</ul>
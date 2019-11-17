<?php

    $data->viewsYear = 1;
    $data->views = 1;

?>

<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-2-third">
                <ol class="breadcrumbs">
                    <li class="breadcrumbs_item"><a href="/">Документы</a></li>
                    <li class="breadcrumbs_item"><?=$data->subject?></li>
                </ol>
                <div class="content">
                    <?php
                        $content = "<p><a href='/pravo/detail?regnum={$data->regnum}'>{$data->subject}</a></p>";

                        $changes = [];

                        foreach ($data->links as $link)
                        {
                            if(!in_array($link->linkname, array_keys($changes)))
                                $changes[$link->linkname] = [];

                            $changes[$link->linkname][] = $link;
                        }

                        if(count($changes))
                        {
                            foreach ($changes as $lk => $linktype)
                            {
                                $content.="<b>{$lk}...</b><br><ul>";

                                foreach ($linktype as $link)
                                    $content .= "<li><a href='/pravo/detail?regnum={$link->regnum}'>{$link->subject}</a></li>";

                                $content.="</ul>";
                            }
                        }

                        echo $content;
                    ?>

                </div>
            </div>
        </div>

        <hr class="hr hr__md"/>

        <div class="row">
            <div class="col-2-third">
                <p class="text-help">
                    Дата публикации (изменения): <?=date('d.m.Y',$data->created_at)?> (<?=date('d.m.Y',$data->updated_at)?>)<br>
                    Просмотров за год (всего): <?=$data->viewsYear?> (<?=$data->views?>)
                </p>
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
</div>



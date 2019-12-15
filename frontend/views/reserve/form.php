<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1 class="h2"><?=$page->title?></h1>

                <?php
                preg_match_all ("/<(collection|gallery|forms)\s(.+?)>(.+?)<\/(collection|gallery|forms)>/is", $page->content, $matches);

                if (!empty($matches[0]))
                    foreach ($matches[0] as $key => $match)
                    {
                        $attributes = parseAttributesFromTag($match);

                        if (!empty($attributes['id']))
                        {
                            $class = 'frontend\widgets\\'.ucwords($matches[1][$key]).'Widget';

                            $page->content = str_replace($match, $class::widget(['attributes'=>$attributes,'page'=>$page]), $page->content);
                        }
                    }

                echo $page->content;
                ?>

                <?=frontend\widgets\FormsWidget::widget(['form'=>$form,'inputs'=>$inputs,'action'=>'', 'collectionRecord' => $record])?>

                <div class="subscribe">
                    <div class="subscribe_left">
                        Поделиться:
                        <div class="ya-share2 subscribe_share" data-services="vkontakte,facebook,odnoklassniki"></div>
                    </div>
                    <div class="subscribe_right"><a class="btn-link" onclick="print()"><i class="material-icons subscribe_print">print</i> Распечатать</a></div>
                </div>
            </div>
            <div class="col-third order-xs-0">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
    </div>
</div>
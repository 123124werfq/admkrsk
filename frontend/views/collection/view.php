<?php
/* @var common\models\Page $page */

/**
 * @param string $tag
 * @return array
 */

?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-2-third">
                <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
            </div>
        </div>
        <div class="row">
            <div class="col-2-third order-xs-1">
            	<div class="content searchable">
                    <?php
                        preg_match_all ("/{(.+?)}/is", $template, $matches);

                        if (!empty($matches[1]))
                        {
                            foreach ($matches[1] as $key => $alias)
                            {
                                if (isset($data[$alias]))
                                {
                                    if (isset($columns[$alias]))
                                        $replace = $columns[$alias]->getValueByType($data[$alias]);
                                    else
                                        $replace = $data[$alias];
                                }
                                else
                                    $replace = '';

                                $template = str_replace('{'.$alias.'}', $replace , $template);
                            }
                        }
                    ?>
                    <?=$template?>
            	</div>
            </div>
            <div class="col-third order-xs-0">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>

        <hr class="hr hr__md"/>

        <div class="row">
            <div class="col-2-third">
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

<?php
    $this->params['page'] = $page;
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
                    <?php if (!empty($template)){?>
                        <?php
                            $output = str_replace('\n', '', common\components\helper\Helper::renderTwig($template,$data));
                            echo common\components\helper\Helper::runContentWidget($output,$page,$data);
                        ?>
                    <?php } else echo 'Не установлен шаблон для вывода страницы'?>
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
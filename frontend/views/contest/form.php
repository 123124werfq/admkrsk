<?php
    use common\models\CstProfile;

    $this->params['page'] = $page;
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1 class="h2"><?=$page->title?></h1>
                <h4 class="h4"><?=$contestname?></h4>                
                <?=common\components\helper\Helper::runContentWidget($page->content,$page)?>

                <?php 
                    if(is_object($profile) && $profile->state == CstProfile::STATE_ACCEPTED){
                ?>
                    <p>Заявка принята к рассмотрению и не может быть изменена</p>
                <?php        
                    }
                    else 
                    {
                        if(is_object($profile) && !empty($profile->comment)){
                ?>
                    <div class="boxed">
                        <p>Замечания для устранения:<br><?=$profile->comment?></p>
                    </div>
                <?php
                        }
                        echo frontend\widgets\FormsWidget::widget(['form'=>$form,'inputs'=>$inputs,'action'=>'', 'collectionRecord' => $record, 'submitLabel' => 'Сохранить']);
                    }
                ?>                

                <div class="subscribe">
                    <div class="subscribe_left">
                        Поделиться:
                        <div class="ya-share2 subscribe_share" data-services="vkontakte,facebook,odnoklassniki"></div>
                    </div>
                    <div class="subscribe_right"><a class="btn-link" onclick="print()"><i class="material-icons subscribe_print">print</i> Распечатать</a></div>
                </div>
            </div>
            <div class="col-third order-xs-0">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$mainpage])?>
            </div>
        </div>
    </div>
</div>
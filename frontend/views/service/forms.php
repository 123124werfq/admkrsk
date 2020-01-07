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
            		<h1><?=$page->title?></h1>

                    <?=$page->content?>

                    <?php foreach ($rubs as $key => $rub){?>
                        <h2><?=$rub->name?></h2>

                        <?php foreach ($services[$rub->id_rub] as $key => $service)
                        {?>
                            <p>
                            <?php
                                $url = '';

                                if (!empty($service->ext_url))
                                    $url = $service->ext_url;
                                else if (count($service->forms)==1)
                                    $url = 'create?id_form='.$service->forms[0]->id_form;

                                if (!empty($url))
                                    echo '<a href="'.$url.'">'.$service->reestr_number.' '.$service->name.'</a>';
                                else
                                    echo $service->reestr_number.' '.$service->name;
                            ?>
                            </p>
                            <?php if (count($service->forms)>1){?>
                            <ul>
                            <?php foreach ($service->forms as $fkey => $form)
                            {
                                echo '<li><a href="create?id_form='.$form->id_form.'">'.$form->fullname.'</li>';
                            }?>
                            </ul>
                        <?php }?>
                        <?php }?>
                    <?php }?>
            	</div>
            </div>
            <div class="col-third order-xs-0">
            	<?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>

        <hr class="hr hr__md"/>

        <?= $this->render('//site/_pagestat', ['data' => $page])?>

    </div>
</div>
<?=frontend\widgets\AlertWidget::widget(['page'=>$page])?>
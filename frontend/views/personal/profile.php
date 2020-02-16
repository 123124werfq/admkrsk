<?php
    $this->params['page'] = $page;
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1 class="h2">Профиль</h1>
                <p><?=$user->esiainfo->getUsertype()?></p>
                <div class="content">
                    <?php
                        if(!$user->esiainfo->is_org){
                    ?>
                        <h3>Основная информация</h3>
                        <p><em>ФИО:</em> <?=$user->esiainfo->last_name?> <?=$user->esiainfo->first_name?> <?=$user->esiainfo->middle_name?></p>

                        <?php if(!empty($user->esiainfo->birthdate)) { ?>
                        <p><em>Дата рождения:</em> <?=$user->esiainfo->birthdate?></p>
                        <?php } ?>
                        <?php if(!empty($user->esiainfo->birthplace)){ ?>
                        <p><em>Место рождения:</em> <?=$user->esiainfo->birthplace?></p>
                        <?php } ?>
                        <?php if(!empty($user->esiainfo->passport_serie)){ ?>
                        <p><em>Паспорт гражданина РФ:</em> <?=$user->esiainfo->passport_serie?> <?=$user->esiainfo->passport_number?>. Выдан <?=$user->esiainfo->passport_date?> <?=$user->esiainfo->passport_issuer?> код подразделения <?=$user->esiainfo->passport_issuer_id?></p>
                        <?php } ?>

                        <?php if(!empty($user->esiainfo->inn) || !empty($user->esiainfo->snils)){ ?>
                        <h3>Идентификаторы</h3>
                        <?php if(!empty($user->esiainfo->inn)){ ?>
                        <p><em>ИНН:</em> <?=$user->esiainfo->inn?></p>
                        <?php } ?>
                        <?php if(!empty($user->esiainfo->snils)){ ?>
                        <p><em>СНИЛС:</em> <?=$user->esiainfo->snils?></p>
                        <?php } ?>
                        <?php } ?>

                        <h3>Контактная информация</h3>
                        <?php if(!empty($user->esiainfo->register_addr)){ ?>
                        <p><em>Адрес регистрации:</em> <?=$user->esiainfo->register_addr?> (<?=empty($user->esiainfo->register_addr_fias)?'ФИАС не указан':$user->esiainfo->register_addr_fias?>)</p>
                        <?php } ?>
                        <?php if(!empty($user->esiainfo->living_addr)){ ?>
                        <p><em>Адрес проживания:</em> <?=$user->esiainfo->living_addr?> (<?=empty($user->esiainfo->living_addr_fias)?'ФИАС не указан':$user->esiainfo->living_addr_fias?>)</p>
                        <?php } ?>
                        <?php if(!empty($user->esiainfo->mobile)){ ?>
                        <p><em>Телефон:</em> <?=$user->esiainfo->mobile?></p>
                        <?php } ?>
                        <?php if(!empty($user->esiainfo->email)){ ?>
                        <p><em>Email:</em> <?=$user->esiainfo->email?></p>
                        <?php } ?>

                        <?php
                            $afirms = $user->getActiveFirms();
                            if($afirms) echo "<ul>";
                            foreach ($afirms as $afirm){
                        ?>
                                <li><a href="/site/asfirm?f=<?=$afirm->oid?>"><?=$afirm->fullname?></a> </li>                               
                            <?php }
                            if($afirms) echo "<ul>";

                            ?>

                    <?php
                        } else {
                            $firm = $user->getCurrentFirm();
                    ?>
                        <h3>Основная информация</h3>
                        <p><em>Наименование:</em> <?=$firm->fullname?></p>
                        <?php if(!empty($firm->ogrn)){ ?>
                        <p><em>ОГРН:</em> <?=$firm->ogrn?></p>
                        <?php } ?>

                        <?php if(!empty($firm->inn)){ ?>
                        <p><em>ИНН:</em> <?=$firm->inn?></p>
                        <?php } ?>

                        <?php if(!empty($firm->kpp)){ ?>
                        <p><em>КПП:</em> <?=$firm->kpp?></p>
                        <?php } ?>

                        <?php if(!empty($firm->leg)){ ?>
                        <p><em>ОПФ:</em> <?=$firm->leg?></p>
                        <?php } ?>

                        <?php if(!empty($firm->main_addr)){ ?>
                        <p><em>Адрес:</em> <?=$firm->main_addr?></p>
                        <?php } ?>

                        <?php } ?>
                    <a href="https://esia.gosuslugi.ru/profile/user/personal?cid=PGU" class="btn btn__block btn__border">Редактировать информацию на Госуслугах</a>
                    <br><br>

                </div>
            </div>
            <div class="col-third order-xs-0">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
    </div>
</div>
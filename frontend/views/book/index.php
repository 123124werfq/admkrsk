<?php

?>
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-2-third">
                    <?php
                        echo frontend\widgets\Breadcrumbs::widget(['page'=>$page])
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2-third order-xs-1">
                    <div class="content">
                        <h1>Предварительная запись</h1>
                        <form action="/book/proceed" method="POST" class="damask-form">
                            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>"/>
                            <div class="form-group">
                                <label for="secondname" class="form-label">Фамилия Имя Отчество</label>
                                <input name="username" value="<?=$esiauser?($esiauser->first_name . ' ' . $esiauser->middle_name. ' ' . $esiauser->last_name):''?>" class="form-control" <?=($esiauser&& ($esiauser->first_name || $esiauser->middle_name || $esiauser->last_name))?'readonly':''?>>
                            </div>
                            <div class="form-group">
                                <label for="tel" class="form-label">Контактный телефон</label>
                                <input name="mobile" value="<?=$esiauser?$esiauser->mobile:''?>" type="tel" class="form-control" <?=($esiauser && $esiauser->mobile)?'readonly':''?>>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Куда</label>
                                <div class="custom-select">
                                    <select name="type">
                                        <?php
                                        foreach (array_keys($commontree) as $block) {
                                            $bparts = explode(":", $block);
                                            echo "<option value='" . $bparts[0] . "'>$bparts[1]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Что</label>
                                <div class="custom-select">
                                    <?php
                                    $first = true;
                                    foreach ($commontree as $nblock=>$block) {
                                        $bparts = explode(":", $nblock);
                                        ?>
                                        <div class="<?=$first?'':'hidden'?> wrap_service wrap_service_<?=$bparts[0]?>">
                                            <select name="service_<?=$bparts[0]?>" data-num="<?=$bparts[0]?>">
                                                <option value='-1'>[не выбрано]</option>
                                                <?php
                                                $first = false;
                                                foreach ($block as $skey => $service)
                                                    echo "<option value='".$skey."'>$service</option>";
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bookdate" class="form-label">Дата</label>
                                <input name="bookdate" class="form-control" type="text" id="datepicker" autocomplete="false">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Время</label>
                                <div class="custom-select">
                                    <select name="time">
                                        <option value='-1'>[не выбрано]</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-end">
                                <div class="form-end_right">
                                    <input type="submit" class="btn btn__secondary" value="Записаться">
                                </div>
                            </div>
                            <div class="form-end"></div>
                        </form>
                    </div>
                </div>
                <div class="col-third order-xs-0">
                    <?php
                        echo frontend\widgets\RightMenuWidget::widget(['page'=>$page])
                    ?>
                </div>
            </div>


        </div>
    </div>

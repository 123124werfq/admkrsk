<?php
/* @var common\models\Page $page */

?>
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-2-third order-xs-1">
                    <div class="content searchable">
                        <h1>Интерактивное голосование</h1>

                        <?php if(!$data) {?>
                            <p>В данный момент голосование не проводится</p>
                        <?php } else { ?>

                            <p>Период проведения: <?= date('d-m-Y H:i', $data->begin)?> - <?= date('d-m-Y H:i', $data->end)?></p>

                            <table>
                                <thead>
                                    <tr>
                                        <td>
                                            №
                                        </td>
                                        <td>
                                            ФИО кандидата
                                        </td>
                                        <td>
                                            Группы должностей
                                        </td>
                                        <td>
                                            Оценка
                                        </td>
                                    </tr>

                                <?php
                                    $count = 1;
                                    foreach ($data->profiles as $profile)
                                    {
                                 ?>
                                    <tr>
                                        <td>
                                            <?=$count++?>
                                        </td>
                                        <td>
                                            <a href="/reserve/profile?id=<?=$profile->id_profile?>"><?=$profile->name?></a>
                                        </td>
                                        <td>
                                            <?php
                                                foreach ($profile->positions as $position)
                                                    echo $position->positionName . "<br>";
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            foreach ($profile->positions as $position)
                                            {
                                                $result = false;
                                                foreach ($votes as $vote)
                                                    if($vote->id_profile==$profile->id_profile && $vote->id_position == $position->id_position)
                                                        $result = $vote->value;

                                                echo ($result?$result:'нет оценки') . "<br>";
                                            }
                                            ?>

                                        </td>
                                    </tr>
                                <?php
                                    }
                                ?>


                                </thead>
                            </table>


                        <?php } ?>

                    </div>
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

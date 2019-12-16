<?php
/* @var common\models\Page $page */

?>
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="" style="width: 100%;">
                    <div class="content">
                        <h1>Интерактивное голосование</h1>

                        <?php if(!$data) {?>
                            <p>В данный момент голосование не проводится</p>
                        <?php } else { ?>

                            <p>Период проведения: <?= date('d-m-Y H:i', $data->begin)?> - <?= date('d-m-Y H:i', $data->end)?></p>

                            <table class="table table-striped vote">
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
                                </thead>
                                <?php
                                    $count = 1;
                                    foreach ($data->profiles as $profile)
                                    {
                                 ?>
                                    <tr>
                                        <th scope="row">
                                            <?=$count++?>
                                        </th>
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
                                                    if($vote->id_profile==$profile->id_profile && $vote->id_record == $position->id_profile_position)
                                                        $result = $vote->value;

                                                switch ($result){
                                                    case 0: echo '<span class="badge secondary">нет оценки</span>'; break;
                                                    case -1: echo '<span class="badge badge-danger">не включать</span>'; break;
                                                    case 1: echo '<span class="badge badge-success">включить</span>'; break;
                                                }

                                                echo "<br>";
                                            }
                                            ?>

                                        </td>
                                    </tr>
                                <?php
                                    }
                                ?>
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

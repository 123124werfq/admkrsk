<?php
/* @var common\models\Page $page */

?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="" style="width: 100%;">
                <div class="content">
                    <h1>Интерактивное голосование</h1>

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
                                <?php foreach($data->experts as $expert){?>
                                <td>
                                    <?=$expert->name?>
                                </td>
                                <?php } ?>
                                <td>
                                    Итого
                                </td>
                            </tr>
                            </thead>
                            <?php
                            $count = 1;
                            $positionTotal = [];
                            foreach ($data->profiles as $profile)
                            {
                                $positionTotal[$profile->id_profile] = [];
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
                                        <?php

                                        foreach ($data->experts as $expert)
                                        {
                                            echo "<td>";

                                            foreach ($profile->positions as $position)
                                            {
                                                if(!isset($positionTotal[$profile->id_profile][$position->id_profile_position]))
                                                    $positionTotal[$profile->id_profile][$position->id_profile_position] = 0;

                                                $result = false;
                                                foreach ($votes as $vote)
                                                    if ($vote->id_expert == $expert->id_expert && $vote->id_profile == $profile->id_profile && $vote->id_record == $position->id_profile_position) {
                                                        $result = $vote->value;
                                                        $rr = $vote->id_record;
                                                        $positionTotal[$profile->id_profile][$position->id_profile_position] += $vote->value;
                                                    }

                                                switch ($result) {
                                                    case 0:
                                                        echo '<span class="badge secondary">нет оценки</span>';
                                                        break;
                                                    case -1:
                                                        echo '<span class="badge badge-danger">отказать</span>';
                                                        break;
                                                    case 1:
                                                        echo '<span class="badge badge-success">включить</span>';
                                                        break;
                                                }

                                                echo "<br>";
                                            }

                                            echo "</td>";
                                        }

                                        ?>
                                    <td>
                                        <?php foreach ($positionTotal[$profile->id_profile] as $posid => $result){
                                            if($result<0)
                                                $final = '<span class="badge badge-danger">отказать</span>';
                                            else if($result>0)
                                                $final = '<span class="badge badge-success">включить</span>';
                                            else
                                                $final = '<span class="badge secondary">нет оценки</span>';

                                            ?>
                                            <?=$final?><br>
                                        <?php }?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>

                </div>
            </div>
        </div>
    </div>
</div>

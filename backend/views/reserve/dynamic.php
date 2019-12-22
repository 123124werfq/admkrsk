<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ServiceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$archive = Yii::$app->request->get('archive');

if(isset($_GET['id']))
    $this->title = 'Результаты голосования';
else
    $this->title = 'Активное голосование';

$this->params['breadcrumbs'][] = $this->title;

/*
if (Yii::$app->user->can('admin.service')) {
    if ($archive)
        $this->params['action-block'][] = Html::a('Все записи', ['index'], ['class' => 'btn btn-default']);
    else
        $this->params['action-block'][] = Html::a('Архив', ['index', 'archive' => 1], ['class' => 'btn btn-default']);

    $this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
}
*/


?>

<div class="service-index">

<?php if(!$data){ ?>
    <h3>Ни одного голосования в данный момент не проводится</h3>
<?php } else {?>
    <p>Период проведения: <?= date('d-m-Y H:i', $data->begin)?> - <?= date('d-m-Y H:i', $data->end)?></p>

    <form actio="" method="POST">
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

                                $comment = $vote->comment;
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

                        echo "<br>$comment";
                    }
                    echo "</td>";
                }

                ?>
                <td>
                    <?php foreach ($positionTotal[$profile->id_profile] as $posid => $result){
                        if($result<0)
                            $final = 'отказать';
                        else if($result>0)
                            $final = 'включить';
                        else
                            $final = '';

                        ?>
                        <select name="results[<?=$profile->id_profile?>][<?=$posid?>]">
                            <option value="0"></option>
                            <option value="-1">отказать</option>
                            <option value="1">включить</option>
                        </select>
                        <?=$final?><br>
                    <?php }?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
        <button class="btn btn-danger">Сохранить результаты</button>
    </form>
<?php } ?>
</div>
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
if($data)
    $this->params['button-block'][] = Html::a('Скачать таблицу', ['spreadsheet','id'=>$data->id_contest], ['class' => 'btn btn-success']);


?>

<div class="service-index" style="overflow-x: scroll">

<?php if(!$data){ ?>
    <h3>Ни одного голосования в данный момент не проводится</h3>
<?php } else {?>
    <p>Период проведения: <?= date('d-m-Y H:i', $data->begin)?> - <?= date('d-m-Y H:i', $data->end)?></p>

    <?php if($data->state == \common\models\HrContest::STATE_FINISHED ){?>
        <h2>ИТОГИ ПОДВЕДЕНЫ</h2>
    <?php } ?>

    <form actio="" method="POST">
        <?= Html::hiddenInput(\Yii::$app->getRequest()->csrfParam, \Yii::$app->getRequest()->getCsrfToken(), []);?>
    <table class="table table-striped vote">
        <thead>
        <tr>
            <td>
                №
            </td>
            <td>
                ФИО кандидата
            </td>
            <td style="min-width: 500px;">
                Группы должностей
            </td>
            <?php foreach($data->experts as $expert){?>
                <td style="min-width: 100px;">
                    <?=$expert->name?>
                </td>
            <?php } ?>
            <td style="min-width: 200px;">
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
                        $comment = '';
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
                            $final = 'спорная';


                        // если голосование уже завершено, то будут реальные результаты. подгрузим их напрямую
                        $rp = \common\models\HrProfilePositions::findOne($posid);
                        $fixedResult = \common\models\HrResult::find()->where(['id_contest' =>  $data->id_contest,'id_profile' => $profile->id_profile, 'id_record' => $rp->id_record_position])->one();

                        $ref = $fixedResult?$fixedResult->result:0;

                        ?>
                        <select name="results[<?=$profile->id_profile?>][<?=$posid?>]">
                            <option value="0"></option>
                            <option value="-1" <?=($ref==-1)?'selected':''?>>отказать</option>
                            <option value="1" <?=($ref==1)?'selected':''?>>включить</option>
                        </select>&nbsp;<?=$final?><br>
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
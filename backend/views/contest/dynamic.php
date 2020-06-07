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
//if(isset($_GET['id']))
//    $this->params['button-block'][] = Html::a('Скачать таблицу', ['spreadsheet','id'=>(int)$_GET['id']], ['class' => 'btn btn-success']);


?>

<div class="service-index" style="overflow-x: scroll">

<?php 
    foreach($votelist as $cindex => $votes){
?>

    <form action="" method="POST">
        <?= Html::hiddenInput(\Yii::$app->getRequest()->csrfParam, \Yii::$app->getRequest()->getCsrfToken(), []);?>
    <table class="table table-striped vote">
        <thead>
        <tr>
            <td>
                №
            </td>
            <td>
                Наименование проекта
            </td>
            <?php foreach($experts[$cindex] as $expertId => $expertName){?>
                <td style="min-width: 100px;">
                    <?=$expertName?>
                </td>
            <?php } ?>
            <td style="min-width: 200px;">
                Итого
            </td>
        </tr>
        </thead>
        <?php foreach($votes as $profileId => $vl){ 
            $result = 0;
        ?>
        <tr>
            <td><?=$profileId?></td>
            <td><?=$vl['name']?></td>
            <?php foreach($experts[$cindex] as $expertId => $expertName){
                $currentVal = $vl['votebyexpert'][$expertId]??0;
                $result += $currentVal;

                $voteName = 'не голосовал';

                if($currentVal>0)
                    $voteName = '<span class="badge badge-success">ЗА</span>';
                else if($currentVal<0)
                    $voteName = '<span class="badge badge-danger">ПРОТИВ</span>';

                ?>
                <td style="min-width: 100px;">
                    <?=$voteName?>
                </td>
            <?php } ?>  
            <td>
                <?php 
                    if($result<0)
                        $final = '<span class="badge badge-danger">ПРОТИВ</span>';
                    else if($result>0)
                        $final = '<span class="badge badge-success">ЗА</span>';
                    else
                        $final = 'спорная';
                    $ref = 0;
                    ?>
                    <!--select name="results">
                        <option value="0"></option>
                        <option value="-1" <?=($ref==-1)?'selected':''?>>ПРОТИВ</option>
                        <option value="1" <?=($ref==1)?'selected':''?>>ЗА</option>
                    </select-->&nbsp;<?=$final?><br>
                </td>         
        </tr>
        <?php } ?>
    </table>
        <!--button class="btn btn-danger">Сохранить результаты</button-->
    </form>

<?php 
    }
?>
</div>
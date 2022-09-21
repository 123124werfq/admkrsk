<?php
/* @var common\models\Page $page */

use yii\helpers\Html;
use common\models\CollectionColumn;


?>
<div class="main">
    <div class="container content">
        <div class="row">
            <div class="col-3-third">
                <h1>Интерактивное голосование</h1>
                <?php
                    if(!empty($contest['name']))
                        echo "<h3>{$contest['name']}</h3>";
                ?>
                <div style="margin-bottom: 40px;">
                    <a href="/contest/vote/<?= Yii::$app->session->get('voteback')?>">&larr; Вернуться к общему списку</a>

                    <?php
                        if($tvote && isset($contest['vote_type']) && $contest['vote_type']=='Баллы')
                        {
                    ?>
                        <p>Вы уже поставили оценку <strong><?=(int)$tvote->value?></strong>, но вы можете изменить своё решение</p>
                    <?php
                        } else {
                    ?>
                    <?php if($tvote) {?>
                        <p>Вы уже проголосовали <?=($tvote->value==1)?'<span class="badge badge-success">ЗА</span>':'<span class="badge badge-danger">ПРОТИВ</span>'?>, но вы можете изменить своё решение</p>
                    <?php }?>
                    <?php 
                        }
                    ?>
                </div>

                <?php
                    if(isset($contest['vote_type']) && $contest['vote_type']=='Баллы')
                    {
                ?>
                    <div>
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=1">1</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=2">2</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=3">3</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=4">4</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=5">5</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=6">6</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=7">7</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=8">8</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=9">9</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=10">10</a>&nbsp;
                    </div>
                <?php
                    } else {
                ?>
                    <a class="btn btn__border" style="background: green !important; color: #fff !important; float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=yes">Проголосовать ЗА</a>&nbsp;
                    <a class="btn btn__border" style="background: red !important; color: #fff !important; float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=no">Проголосовать ПРОТИВ</a>
                <?php 
                    }
                ?>
                <?php
                    if (!empty($collectionRecord->collection->form->template)){
                        echo '<a class="btn btn__border" style="background: #8F1A1E !important; color: #fff !important;" download="'.$collectionRecord->id_record.'.docx" href="/collection/word?id_record='.$collectionRecord->id_record.'">Скачать информацию о проекте</a>';
                    }
                ?>
                <hr class="hr hr__md"/>
                <?php echo frontend\widgets\CollectionRecordWidget::widget([
                    'collectionRecord'=>$collectionRecord,
                    'renderTemplate'=>true,
                ]);?>
                <?php
                    if(isset($contest['vote_type']) && $contest['vote_type']=='Баллы')
                    {
                ?>
                    <div>
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=1">1</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=2">2</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=3">3</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=4">4</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=5">5</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=6">6</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=7">7</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=8">8</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=9">9</a>&nbsp;
                    <a class="btn btn__border" style="float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=10">10</a>&nbsp;
                    </div>
                <?php
                    } else {
                ?>
                    <a class="btn btn__border" style="background: green !important; color: #fff !important; float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=yes">Проголосовать ЗА</a>&nbsp;
                    <a class="btn btn__border" style="background: red !important; color: #fff !important; float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=no">Проголосовать ПРОТИВ</a>
                <?php 
                    }
                ?>                <?php
                    if (!empty($collectionRecord->collection->form->template)){
                        echo '<a class="btn btn__border" style="background: #8F1A1E !important; color: #fff !important;" download="'.$collectionRecord->id_record.'.docx" href="/collection/word?id_record='.$collectionRecord->id_record.'">Скачать информацию о проекте</a>';
                    }
                ?>
                <hr class="hr hr__md"/>
            </div>
        </div>
    </div>
</div>
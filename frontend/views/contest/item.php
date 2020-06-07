<?php
/* @var common\models\Page $page */

use yii\helpers\Html;
use common\models\CollectionColumn;

?>
<div class="main">
    <div class="container content">
        <div class="row">
            <div class="col-3-third">
                <div style="margin-bottom: 40px;"><a href="/contest/vote">&larr; Вернуться к общему списку</a></div>
                <a class="btn btn__border" style="background: green !important; color: #fff !important; float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=yes">Проголосовать ЗА</a>
                &nbsp;
                <a class="btn btn__border" style="background: red !important; color: #fff !important; float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=no">Проголосовать ПРОТИВ</a>
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
                <a class="btn btn__border" style="background: green !important; color: #fff !important; float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=yes">Проголосовать ЗА</a>
                &nbsp;
                <a class="btn btn__border" style="background: red !important; color: #fff !important; float:right;" href="/contest/item/<?=$collectionRecord->id_record?>?vote=no">Проголосовать ПРОТИВ</a>
                <?php
                    if (!empty($collectionRecord->collection->form->template)){
                        echo '<a class="btn btn__border" style="background: #8F1A1E !important; color: #fff !important;" download="'.$collectionRecord->id_record.'.docx" href="/collection/word?id_record='.$collectionRecord->id_record.'">Скачать информацию о проекте</a>';
                    }
                ?>
                <hr class="hr hr__md"/>
            </div>
        </div>
    </div>
</div>
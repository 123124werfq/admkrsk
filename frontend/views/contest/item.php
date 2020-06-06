<?php
/* @var common\models\Page $page */

use yii\helpers\Html;
use common\models\CollectionColumn;

?>
<div class="main">
    <div class="container">

        <div class="row">
            <div class="col-2-third">
                <hr class="hr hr__md"/>
                <?php
                    if (!empty($collectionRecord->collection->form->template)){
                        echo '<a class="btn btn-danger" download="'.$collectionRecord->id_record.'.docx" href="/collection/word?id_record='.$collectionRecord->id_record.'">Скачать информацию о проекте</a>';
                    }
                ?>
                <?php echo frontend\widgets\CollectionRecordWidget::widget([
                    'collectionRecord'=>$collectionRecord,
                    'renderTemplate'=>true,
                ]);?>

            </div>
        </div>
    </div>
</div>
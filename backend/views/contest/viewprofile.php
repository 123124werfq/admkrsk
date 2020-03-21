<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppeal */

$this->title = $model->id_profile;
$this->params['breadcrumbs'][] = ['label' => 'Анкета кандидата', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="service-appeal-view">

    <div class="ibox">
        <div class="ibox-content">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'id_profile',
                        'label' => '№'
                    ],
                    'created_at:date',
                    [
                        'attribute' => 'state',
                        'label' => 'Статус',
                        'value' => function($model){
                                return $model->statename;
                        }
                    ]
                ],
            ]) ?>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-content">
            <form action="" method="GET">
                <input type="hidden" name="id" value="<?=$model->id_profile?>">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>"/>
                <div class="form-group field-page-content">
                        <label class="control-label" for="page-content">Комментарий от модератора</label>
                        <textarea id="page-content" class="redactor" name="comment" rows="6" aria-hidden="true"><?=$model->comment?></textarea>
                        <div class="help-block"></div>
                </div>
                <input type="submit" value="Отправить">                   
            </form>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-content">

            <?php echo frontend\widgets\CollectionRecordWidget::widget(['collectionRecord'=>$record]);?>

        </div>
    </div>
</div>

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

            <?php echo frontend\widgets\CollectionRecordWidget::widget(['collectionRecord'=>$record]);?>

        </div>
    </div>
</div>

<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppeal */

$this->title = $model->id_appeal;
$this->params['breadcrumbs'][] = ['label' => 'Обращения', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if (!empty($model->collectionRecord->collection->form->template) || !empty($model->collectionRecord->collection->form->service->template))
    $this->params['button-block'][] = Html::a('Скачать документ', ['doc','id'=>$model->id_appeal], ['class' => 'btn btn-success']);

\yii\web\YiiAsset::register($this);
?>
<div class="service-appeal-view">

    <div class="ibox">
        <div class="ibox-content">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                          'attribute' => 'id_request',
                        'label' => '№'
                    ],
                    'created_at:date',
                    [
                        'attribute' => 'state',
                        'label' => 'Статус',
                        'value' => function($model){
                            switch ($model->state){
                                case 0: return 'Ожидает обработки';
                                case 1: return 'Отправлено';
                                case 2: return 'Ответ получен';
                                case 88: return 'Закрыто';
                            }
                        }
                    ]
                ],
            ]) ?>
        </div>
    </div>

    <div class="ibox">
        <div class="ibox-content">
        <table class="table table-striped">
            <thead>
            <tr>
                <td><strong>Поле</strong></td>
                <td><strong>Значение</strong></td>
            </tr>
            </thead>
            <tbody>
        <?php foreach ($formFields as $alias => $field) {

            foreach ($attachments as $filename)
            {
                $pi = pathinfo($filename);

                if("[{$pi['filename']}]" == $field['value'])
                    $field['value'] = "<a href=$filename target='_blank'>Скачать</a>";
            }
            ?>
            <tr>
                <td><?=$field['name']?></td>
                <td><?=$field['value']?></td>
            </tr>
        <?php } ?>
            </tbody>
        </table>
        </div>
    </div>
</div>

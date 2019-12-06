<?php

use common\models\Question;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Poll */

$this->title = $model->pageTitle;
$this->params['breadcrumbs'][] = ['label' => $model->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('История', ['history', 'id' => $model->id_poll], ['class' => 'btn btn-default']);
$this->params['button-block'][] = Html::a('Экспорт', ['export', 'id' => $model->id_poll], ['class' => 'btn btn-warning']);
$this->params['button-block'][] = Html::a('Добавить вопрос', ['create-question', 'id_poll' => $model->id_poll], ['class' => 'btn btn-success']);
$this->params['button-block'][] = Html::a('Редактировать', ['update', 'id' => $model->id_poll], ['class' => 'btn btn-primary']);

if ($model->isDeleted()) {
    $this->params['button-block'][] = Html::a('Восстановить', ['undelete', 'id' => $model->id_poll], ['class' => 'btn btn-danger']);
} else {
    $this->params['button-block'][] = Html::a('Удалить', ['delete', 'id' => $model->id_poll], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
            'method' => 'post',
        ],
    ]);
}
?>
<div class="poll-view">
    <div class="ibox">
        <div class="ibox-content">

            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
//                    'id_poll',
                    [
                        'attribute' => 'status',
                        'value' => $model->statusName,
                    ],
                    'title',
                    'description:html',
                    'result:html',
                    'is_anonymous:boolean',
                    'is_hidden:boolean',
                    'date_start:datetime',
                    'date_end:datetime',
//                    'created_at:datetime',
//                    [
//                        'attribute' => 'created_by',
//                        'format' => 'raw',
//                        'value' => $model->createdBy ? Html::a($model->createdBy->username, ['/user/view', 'id' => $model->createdBy->id]) : null,
//                    ],
//                    'updated_at:datetime',
//                    [
//                        'attribute' => 'updated_by',
//                        'format' => 'raw',
//                        'value' => $model->updatedBy ? Html::a($model->updatedBy->username, ['/user/view', 'id' => $model->updatedBy->id]) : null,
//                    ],
//                    'deleted_at:datetime',
//                    [
//                        'attribute' => 'deleted_by',
//                        'format' => 'raw',
//                        'value' => $model->deletedBy ? Html::a($model->deletedBy->username, ['/user/view', 'id' => $model->deletedBy->id]) : null,
//                    ],
                ],
                'template' => '<tr><th{captionOptions} width="25%">{label}</th><td{contentOptions}>{value}</td></tr>',
            ]) ?>

        </div>
    </div>
</div>

<?php foreach ($model->questions as $question): ?>

    <div class="poll-view">
        <div class="ibox">
            <div class="ibox-title">
                <div class="button-block text-right">
                    <?= Html::a('История', ['history-question', 'id' => $question->id_poll_question], ['class' => 'btn btn-default']); ?>
                    <?= Html::a('Редактировать', ['update-question', 'id' => $question->id_poll_question], ['class' => 'btn btn-primary']); ?>
                    <?= Html::a('Удалить', ['delete-question', 'id' => $question->id_poll_question], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                            'method' => 'post',
                        ],
                    ]); ?>
                </div>
            </div>
            <div class="ibox-content">
                <?= DetailView::widget([
                    'model' => $question,
                    'attributes' => [
//                        'id_poll_question',
                        [
                            'attribute' => 'type',
                            'value' => $question->typeName,
                        ],
                        'question',
                        'description',
                        'ord',
                        'is_option:boolean',
                        'is_hidden:boolean',
                        [
                            'attribute' => 'chart_type',
                            'value' => $question->chartTypeName,
                        ],
                        [
                            'attribute' => 'answers',
                            'label' => 'Ответы',
                            'format' => 'raw',
                            'value' => function (Question $model) {
                                $results = ArrayHelper::map($model->results, 'id_poll_answer', 'percent');
                                $answers = [];
                                foreach ($model->answers as $answer) {
                                    $answers[$answer->id_poll_answer] = '<div class="progress progress-bar-default">
                                            <div style="width: ' . $results[$answer->id_poll_answer] . '%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="' . $results[$answer->id_poll_answer] . '" role="progressbar" class="progress-bar">
                                                ' . $results[$answer->id_poll_answer] . '%
                                            </div>
                                        </div>';
                                    $answers[$answer->id_poll_answer] .= $answer->getAttributeLabel('answer') . ': ' . $answer->answer;
                                    if ($answer->description) {
                                        $answers[$answer->id_poll_answer] .= '<br>' . $answer->getAttributeLabel('description') . ': ' . $answer->description;
                                    }
                                }
                                return $answers ? implode('<hr>', $answers) : null;
                            },
                        ],
//                        'created_at:datetime',
//                        [
//                            'attribute' => 'created_by',
//                            'format' => 'raw',
//                            'value' => $question->createdBy ? Html::a($question->createdBy->username, ['/user/view', 'id' => $question->createdBy->id]) : null,
//                        ],
//                        'updated_at:datetime',
//                        [
//                            'attribute' => 'updated_by',
//                            'format' => 'raw',
//                            'value' => $question->updatedBy ? Html::a($question->updatedBy->username, ['/user/view', 'id' => $question->updatedBy->id]) : null,
//                        ],
//                        'deleted_at:datetime',
//                        [
//                            'attribute' => 'deleted_by',
//                            'format' => 'raw',
//                            'value' => $question->deletedBy ? Html::a($question->deletedBy->username, ['/user/view', 'id' => $question->deletedBy->id]) : null,
//                        ],
                    ],
                    'template' => '<tr><th{captionOptions} width="25%">{label}</th><td{contentOptions}>{value}</td></tr>',
                ]) ?>
            </div>
        </div>
    </div>

<?php endforeach; ?>

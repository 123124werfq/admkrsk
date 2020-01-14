<?php

/* @var $this yii\web\View */
/* @var $pollForm frontend\models\PollForm */
/* @var $poll common\models\Poll */
/* @var $page common\models\Page */

/* @var $form yii\widgets\ActiveForm */

use common\models\Question;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->params['page'] = $page;
?>
<div class="main">
    <div class="container">
        <?= frontend\widgets\Breadcrumbs::widget(['page' => $page]) ?>
        <div class="row">
            <div class="col-2-third">
                <h1><?= $poll->title ?></h1>

                <?php if ($poll->date_start && $poll->date_end): ?>
                    <p class="title-date">с <?= Yii::$app->formatter->asDate($poll->date_start, 'd MMMM yyyy') ?>
                        по <?= Yii::$app->formatter->asDate($poll->date_end, 'd MMMM yyyy') ?></p>
                <?php endif; ?>
            </div>
            <div class="col-third">
                <?= frontend\widgets\RightMenuWidget::widget(['page' => $page]) ?>
            </div>
        </div>

        <hr class="hr hr__large">

        <div class="row">
            <div class="col-2-third">
                <?php if ($pollForm->hasErrors()): ?>
                    <div class="polls__question-error">
                        <?= Html::errorSummary($pollForm, [
                            'showAllErrors' => true,
                            'header' => '',
                        ]) ?>
                    </div>
                <?php endif; ?>

                <?= $poll->description ?>

                <?php if (!$poll->isExpired() && !$poll->isPassed()): ?>

                    <?php if (Yii::$app->user->isGuest && !$poll->is_anonymous): ?>

                        <h3>Для участия в опросе, пожалуйста, авторизуйтесь.</h3>

                        <p>Регистрация в Единой системе идентификации и аутентификации (ЕСИА)</p>
                        <p>Авторизация через портал государственных услуг Российской Федерации (www.gosuslugi.ru)</p>

                        <div class="row mt-3 mb-4">
                            <div class="col-fourth">
                                <?= Html::a('Регистрация', ['/site/signup'],
                                    ['class' => 'btn btn__block btn__border']) ?>
                            </div>
                            <div class="col-fourth">
                                <?= Html::a('Войти', ['/site/login'], ['class' => 'btn btn__block btn__secondary']) ?>
                            </div>
                        </div>

                    <?php endif; ?>

                    <?php $form = ActiveForm::begin(['scrollToError' => true]); ?>

                    <?php $question_index = 0; ?>

                    <?php foreach ($pollForm->votes as $vote): ?>

                        <h2>Вопрос <?= ++$question_index ?>. <?= $vote->question->question ?></h2>

                        <div class="content">
                            <?= $vote->question->description ?>

                            <?php if ($vote->question->type != Question::TYPE_RANGING): ?>

                                <div class="boxed mb-5<?= $vote->hasErrors() ? ' has-error' : '' ?>">

                                    <?php if ($vote->hasErrors()): ?>
                                        <div class="polls__question-error"><?= $form->errorSummary($vote, ['header' => '']) ?></div>
                                    <?php endif; ?>

                                    <?php if ($vote->question->type != Question::TYPE_FREE_FORM): ?>

                                        <?php foreach ($vote->question->answers as $answer): ?>

                                            <?php if ($vote->question->type == Question::TYPE_ONLY): ?>

                                                <div class="radio-group">
                                                    <label class="radio">
                                                        <?= Html::radio(Html::getInputName($vote, "[{$vote->question->id_poll_question}]answer_ids[]"),
                                                            in_array($answer->id_poll_answer, $vote->answer_ids), [
                                                                'value' => $answer->id_poll_answer,
                                                                'class' => 'radio_control',
                                                            ]) ?>
                                                        <span class="radio_label"><?= $answer->answer ?></span>
                                                    </label>

                                                    <?php if ($answer->description): ?>
                                                        <span class="tooltip top"
                                                              data-tipso="<?= Html::encode($answer->description) ?>">?</span>
                                                    <?php endif; ?>
                                                </div>

                                            <?php elseif ($vote->question->type == Question::TYPE_MULTIPLE): ?>

                                                <div class="checkbox-group">
                                                    <label class="checkbox">
                                                        <?= Html::checkbox(Html::getInputName($vote, "[{$vote->question->id_poll_question}]answer_ids[]"),
                                                            in_array($answer->id_poll_answer, $vote->answer_ids), [
                                                                'value' => $answer->id_poll_answer,
                                                                'class' => 'checkbox_control',
                                                            ]) ?>
                                                        <span class="checkbox_label"><?= $answer->answer ?></span>
                                                    </label>

                                                    <?php if ($answer->description): ?>
                                                        <span class="tooltip top"
                                                              data-tipso="<?= Html::encode($answer->description) ?>">?</span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                        <?php endforeach; ?>

                                    <?php endif; ?>

                                    <?php if ($vote->question->type == Question::TYPE_FREE_FORM || $vote->question->is_option): ?>

                                        <div class="form-group">
                                            <label for="message-<?= $vote->question->id_poll_question ?>"
                                                   class="form-label">Текст
                                                обращения либо запроса*</label>
                                            <?= Html::textarea(Html::getInputName($vote, "[{$vote->question->id_poll_question}]option"),
                                                $vote->option, [
                                                    'maxlength' => 280,
                                                    'class' => 'form-control',
                                                ]) ?>
                                        </div>

                                    <?php endif; ?>

                                </div>

                            <?php else: ?>

                                <div class="boxed boxed__invert sortable mb-5">

                                    <?php foreach ($vote->answers as $answer_index => $answer): ?>

                                        <div class="box-item sortable-item ui-state-default">
                                            <div class="sort-control">
                                                <?= Html::hiddenInput(Html::getInputName($vote, "[{$vote->question->id_poll_question}]answer_ids[]"),
                                                    $answer->id_poll_answer) ?>
                                                <button type="button" class="sort-control_up"><i class="material-icons">keyboard_arrow_up</i>
                                                </button>
                                                <div class="sort-control_value"><?= $answer_index + 1 ?></div>
                                                <button type="button" class="sort-control_down"><i
                                                            class="material-icons">keyboard_arrow_down</i></button>
                                            </div>
                                            <h5 class="box-item_label"><?= $answer->answer ?></h5>
                                        </div>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                    <div class="form-end">
                        <div class="form-end_right">
                            <input type="submit" value="Завершить опрос" class="btn btn__secondary">
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                <?php else: ?>

                    Результаты опроса

                    <?php foreach ($poll->questions as $question_index => $question): ?>

                        <h2>Вопрос <?= $question_index + 1 ?>.</h2>

                        <p class="lead"><?= $question->question ?></p>

                        <?php if ($question->type != Question::TYPE_FREE_FORM): ?>

                            <?php if ($question->chart_type == Question::CHART_TYPE_GRAPH): ?>

                                <div class="chart-holder">
                                    <canvas class="chart" data-chart-type="graph" data-y-type="%" data-color-dark
                                            data-values="<?= $question->dataValues ?>"></canvas>
                                    <div class="chart-labels"><?= $question->chartLabels ?></div>
                                </div>

                            <?php elseif ($question->chart_type == Question::CHART_TYPE_PIE): ?>

                                <div class="chart-holder chart-holder__pie">
                                    <canvas class="chart" data-chart-type="pie" data-color-dark
                                            data-values="<?= $question->dataValues ?>"></canvas>
                                    <div class="chart-labels"><?= $question->chartLabels ?></div>
                                </div>

                            <?php elseif ($question->chart_type == Question::CHART_TYPE_BAR_H): ?>

                                <div class="chart-holder">
                                    <canvas class="chart" data-chart-type="bar-h" data-color-dark
                                            data-values="<?= $question->dataValues ?>"></canvas>
                                    <div class="chart-labels"><?= $question->chartLabels ?></div>
                                </div>

                            <?php else: ?>

                                <div class="chart-holder chart-holder__bar-v" height="270">
                                    <canvas class="chart" data-chart-type="bar-v" data-color-dark
                                            data-values="<?= $question->dataValues ?>"></canvas>
                                    <div class="chart-labels"><?= $question->chartLabels ?></div>
                                </div>

                            <?php endif; ?>

                            <p class="text-help">

                                Ответы:<br>

                                <?php foreach ($question->answers as $answer_index => $answer): ?>

                                    <?= $answer_index + 1 ?>. <?= $answer->answer ?><br>

                                <?php endforeach; ?>

                            </p>

                        <?php else: ?>

                            Ответило <?= $question->freeVotesCount ?> чел.

                        <?php endif; ?>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>
        </div>

        <hr class="hr__md">

        <p class="text-help">
            Дата публикации (изменения): <span class="publish-date"><?= date('d.m.Y', $poll->created_at) ?></span> (<span class="update-date"><?= date('d.m.Y',
                    $poll->updated_at) ?></span>)<br>
            Просмотров за год (всего): <?= $poll->viewsYear ?> (<?= $poll->views ?>)
        </p>

        <div class="row">
            <div class="col-2-third">
                <div class="subscribe">
                    <div class="subscribe_left">
                        Поделиться:
                        <div class="ya-share2 subscribe_share" data-services="vkontakte,facebook,odnoklassniki"></div>
                    </div>
                    <div class="subscribe_right"><a class="btn-link" onclick="print()"><i
                                    class="material-icons subscribe_print">print</i> Распечатать</a></div>
                </div>
            </div>
        </div>

    </div>
</div>
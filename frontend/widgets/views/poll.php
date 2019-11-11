<?php
/* @var \common\models\Question $question */

use common\models\Question;
?>
<div class="quiz">
    <div class="container">
        <h2 class="section-title section-title__invert"><?= $question->poll->title ?></h2>
        <div class="row">
            <div class="col-2-third">
                <?php if ($question): ?>

                    <h3 class="quiz-title text-invert"><?= $question->question ?></h3>

                    <?php if ($question->chart_type == Question::CHART_TYPE_GRAPH): ?>

                        <div class="chart-holder">
                            <canvas class="chart" data-chart-type="graph" data-y-type="%" data-values="<?= $question->dataValues ?>"></canvas>
                            <div class="chart-labels"><?= $question->chartMainLabels ?></div>
                        </div>

                    <?php elseif ($question->chart_type == Question::CHART_TYPE_PIE): ?>

                        <div class="chart-holder chart-holder__pie">
                            <canvas class="chart" data-chart-type="pie" data-values="<?= $question->dataValues ?>"></canvas>
                            <div class="chart-labels"><?= $question->chartMainLabels ?></div>
                        </div>

                    <?php elseif ($question->chart_type == Question::CHART_TYPE_BAR_H): ?>

                        <div class="chart-holder">
                            <canvas class="chart" data-chart-type="bar-h" data-values="<?= $question->dataValues ?>"></canvas>
                            <div class="chart-labels"><?= $question->chartMainLabels ?></div>
                        </div>

                    <?php else: ?>

                        <div class="chart-holder chart-holder__bar-v" height="270">
                            <canvas class="chart" data-chart-type="bar-v" data-values="<?= $question->dataValues ?>"></canvas>
                            <div class="chart-labels"><?= $question->chartMainLabels ?></div>
                        </div>

                    <?php endif; ?>

                <?php endif; ?>
            </div>
            <div class="col-third">
                <h4 class="visible-accessability">Общественные проекты</h4>
                <div class="quiz-sidebar">
                    <ul class="quiz-menu">
                        <?php if (!empty($blockVars['menu'])){
                            $menu = common\models\Menu::findOne($blockVars['menu']->value);
                            if (!empty($menu))
                                foreach ($menu->links as $key => $link) {?>
                                <li class="gid-menu_item"><a href="<?=(!empty($link->id_page))?$link->page->getUrl():$link->url?>" class="gid-menu_link"><?=$link->label?></a></li>
                        <?php }}?>
                    </ul>
                    <a href="#" class="btn btn__block">Написать сообщение</a>
                </div>
            </div>
        </div>
    </div>
</div>
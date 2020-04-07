<?php

/* @var $this yii\web\View */
/* @var $searchModel \frontend\models\search\PollSearch */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $page Page */

use common\models\Page;
use common\models\Poll;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ListView;

$this->params['page'] = $page;
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third">
                <h1>Опросы</h1>
            </div>
            <div class="col-third">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
    </div>
    <div class="section-secondary">
        <div class="container">
            <div class="row">
                <div class="col-2-third">
                    <div class="pull-list">
                        <?= ListView::widget([
                            'dataProvider' => $dataProvider,
                            'itemView' => '_poll',
                            'viewParams' => [
                                'archive' => $searchModel->archive,
                            ],
                            'layout' => "{items}",
                        ]) ?>
                    </div>

                    <?php if (count($dataProvider->pagination->links) > 1): ?>

                        <div class="pager">
                            <?= Html::a('keyboard_arrow_left', $dataProvider->pagination->links['prev'] ?? null, ['class' => 'pager_prev material-icons', 'title' => 'Назад']) ?>

                            <?= LinkPager::widget([
                                'pagination' => $dataProvider->pagination,
                                'options' => ['class' => 'pager_list'],
                                'linkContainerOptions' => ['class' => 'pager_item'],
                                'activePageCssClass' => ['class' => 'selected'],
                                'prevPageLabel' => false,
                                'nextPageLabel' => false,
                            ]); ?>

                            <?= Html::a('keyboard_arrow_right', $dataProvider->pagination->links['next'] ?? null, ['class' => 'pager_next material-icons', 'title' => 'Далее']) ?>
                        </div>

                    <?php endif; ?>

                </div>
                <div class="col-third">
                    <div class="statbar">
                        <div class="statbar_item">
                            <h5 class="statbar-item_title">Всего активных опросов:</h5>
                            <div class="statbar-item_value"><?= Poll::activeCount() ?></div>
                        </div>
                        <div class="statbar_item">
                            <h5 class="statbar-item_title">Количество голосов пользователей:</h5>
                            <div class="statbar-item_value"><?= Poll::voitesCount() ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

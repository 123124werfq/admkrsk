<?php
/* @var common\models\Page $page */

/**
 * @param string $tag
 * @return array
 */
$this->params['page'] = $page;

$user = Yii::$app->user->identity;
$userActiveDirectory = null;
if ($user) {
    $userActiveDirectory = $user->activeDirectoryUser;
}

?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-2-third">
                <?= frontend\widgets\Breadcrumbs::widget(['page' => $page]) ?>
            </div>
        </div>
        <div>
            <?php if ($userActiveDirectory && $userActiveDirectory->can('backend.page')):  ?>
            <a
                    style="border-bottom: 1px solid black;
                    border-bottom-style: dashed;"
                    href="<?= Yii::$app->params['backendUrl'] ?>/page/update?id=<?= $page->id_page ?>">
                редактировать страницу
            </a>
            <?php endif; ?>
        </div>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <div class="content searchable">
                    <h1><?= $page->title ?></h1>
                    <?=$page->hidden_message?>
                </div>
            </div>
            <div class="col-third order-xs-0">
                <?= frontend\widgets\RightMenuWidget::widget(['page' => $page]) ?>
            </div>
        </div>
    </div>
</div>
<?= frontend\widgets\AlertWidget::widget(['page' => $page]) ?>
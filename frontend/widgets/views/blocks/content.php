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
                    <?=common\components\helper\Helper::runContentWidget($page->content,$page)?>

                    <?php if (!empty($page->medias)) { ?>
                        <div class="file-list">
                            <?php foreach ($page->medias as $key => $media) { ?>
                                <div class="file-item">
                                    <!--div class="file-td file-td__date"></div-->
                                    <div class="file-td file-td__name"><?= empty($media->description)?$media->name:$media->description ?></div>
                                    <div class="file-td file-td__type"><?= $media->extension ?>
                                        , <?= round($media->size / 1024, 2) ?>кБ
                                    </div>
                                    <div class="file-td file-td__control">
                                        <a href="<?=$media->getUrl()?>" class="btn btn__secondary btn__block-sm" download="<?=$media->downloadName()?>">Скачать <i class="material-icons btn-icon btn-icon__right btn-icon__sm">get_app</i></a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="col-third order-xs-0">
                <?= frontend\widgets\RightMenuWidget::widget(['page' => $page]) ?>
            </div>
        </div>
        <hr class="hr hr__md"/>
        <?= $this->render('//site/_pagestat', ['data' => $page])?>
    </div>
</div>
<?php
/* @var common\models\Page $page */

/**
 * @param string $tag
 * @return array
 */
function parseAttributesFromTag($tag)
{
    $pattern = '/(\w+)=[\'"]([^\'"]*)/';

    preg_match_all($pattern, $tag, $matches, PREG_SET_ORDER);

    $result = [];
    foreach ($matches as $match) {
        $attrName = $match[1];
        $attrValue = is_numeric($match[2]) ? (int)$match[2] : trim($match[2]);
        $result[$attrName] = $attrValue;
    }

    return $result;
}

?>
    <div class="main">
        <div class="container">
            <div class="row">
                <div class="col-2-third">
                    <?= frontend\widgets\Breadcrumbs::widget(['page' => $page]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-2-third order-xs-1">
                    <div class="content searchable">
                        <h1><?= $page->title ?></h1>
                        <?php
                        preg_match_all("/<(collection|gallery|forms)\s(.+?)>(.+?)<\/(collection|gallery|forms)>/is", $page->content, $matches);

                        if (!empty($matches[0]))
                            foreach ($matches[0] as $key => $match) {
                                $attributes = parseAttributesFromTag($match);

                                if (!empty($attributes['id'])) {
                                    $class = 'frontend\widgets\\' . ucwords($matches[1][$key]) . 'Widget';

                                    $page->content = str_replace($match, $class::widget(['attributes' => $attributes, 'page' => $page]), $page->content);
                                }
                            }

                        echo $page->content;
                        ?>

                        <?php if (!empty($page->medias)) { ?>
                            <div class="file-list">
                                <?php foreach ($page->medias as $key => $media) { ?>
                                    <div class="file-item">
                                        <div class="file-td file-td__date"><?= $media->created_at ?></div>
                                        <div class="file-td file-td__name"><?= $media->name ?></div>
                                        <div class="file-td file-td__type"><?= $media->extension ?>
                                            , <?= round($media->size / 1024, 2) ?>кБ
                                        </div>
                                        <div class="file-td file-td__control">
                                            <a href="<?= $media->getUrl() ?>" class="btn btn__secondary btn__block-sm"
                                               download>Скачать <i
                                                        class="material-icons btn-icon btn-icon__right btn-icon__sm">get_app</i></a>
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

            <div class="row">
                <div class="col-2-third">
                    <p class="text-help">
                        Дата публикации (изменения): <?= date('d.m.Y', $page->created_at) ?>
                        (<?= date('d.m.Y', $page->updated_at) ?>)<br>
                        Просмотров за год (всего): <?= $page->viewsYear ?> (<?= $page->views ?>)
                    </p>
                    <div class="subscribe">
                        <div class="subscribe_left">
                            Поделиться:
                            <div class="ya-share2 subscribe_share"
                                 data-services="vkontakte,facebook,odnoklassniki"></div>
                        </div>
                        <div class="subscribe_right"><a class="btn-link" onclick="print()"><i
                                        class="material-icons subscribe_print">print</i> Распечатать</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?= frontend\widgets\AlertWidget::widget(['page' => $page]) ?>
<?php
use yii\bootstrap\ActiveForm;
use common\models\Media;

/* @var common\models\Page $page */

/**
 * @param string $tag
 * @return array
 */
$this->params['page'] = $page;

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
                    <h1>Документы организации</h1>

                    <?=frontend\widgets\FormsWidget::widget(['form'=>$form, 'action'=>'', 'submitLabel' => 'Добавить']);?>

                    <?php if (!empty($files)) { ?>
                        <br/>
                        <h2>Загруженные файлы</h2>
                        <div class="file-list">
                            <?php foreach ($files as $key => $data) {
                                    if (!empty($data['file'][0]['id']))
                                        $media = Media::findOne($data['file'][0]['id']);

                                    if (!empty($media))
                                    {
                             ?>
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
                            <?php }
                        }?>
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

<?= frontend\widgets\AlertWidget::widget(['page' => $page]) ?>
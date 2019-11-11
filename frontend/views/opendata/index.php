<?php

/* @var $this yii\web\View */
/* @var $searchModel \frontend\models\search\OpendataSearch */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $page \common\models\Page */

use common\models\Opendata;
use yii\helpers\Html;

$list = Yii::$app->publicStorage->getMetadata(Opendata::OPENDATA_LIST_PATH);
$listUrl = Yii::$app->publicStorage->getPublicUrl(Opendata::OPENDATA_LIST_PATH);
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third">
                <h1>Реестр наборов открытых данных</h1>
            </div>
            <div class="col-third order-xs-0">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="container">
        <div class="row">
            <div class="col-2-third">
                <div class="file-list">
                    <div class="file-item">
                        <div class="file-td file-td__name">Экспорт реестра (CSV)</div>
                        <div class="file-td file-td__type">CSV, <?= Yii::$app->formatter->asShortSize($list['size'], 0) ?></div>
                        <div class="file-td file-td__control">
                            <a href="<?= $listUrl ?>" class="btn btn__secondary btn__block-sm">Скачать <i class="material-icons btn-icon btn-icon__right btn-icon__sm">get_app</i></a>
                        </div>
                    </div>
                </div>

                <div class="content">
                    <div class="table-responsive">
                        <table class="label-table label-table__no-width">
                            <tr>
                                <th class="w-20">Порядковый номер</th>
                                <th>Наименование набора данных</th>
                                <th class="w-20">Формат набора данных</th>
                            </tr>
                            <?php foreach ($dataProvider->getModels() as $key => $item): ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td>
                                        <?= Html::a($item->title, ['/opendata/view', 'id' => $item->identifier]) ?>
                                    </td>
                                    <td>CSV</td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>

                <div class="subscribe">
                    <div class="subscribe_left">
                        Поделиться:
                        <div class="ya-share2 subscribe_share" data-services="vkontakte,facebook,odnoklassniki"></div>
                    </div>
                    <div class="subscribe_right"><a class="btn-link" onclick="print()"><i class="material-icons subscribe_print">print</i> Распечатать</a></div>
                </div>
            </div>
        </div>
    </div>
</div>


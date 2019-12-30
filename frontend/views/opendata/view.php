<?php

/* @var $this yii\web\View */
/* @var $model \common\models\Opendata */
/* @var $page \common\models\Page */

use yii\helpers\Html;

$meta = $model->lastData->metadata;
$metaUrl = $model->lastData->url;
$lastDataUrl = $model->lastData->url;
$lastDataStructureUrl = $model->lastData->structure->url;
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1><?= $model->title ?></h1>

                <div class="file-list">
                    <div class="file-item">
                        <div class="file-td file-td__date"><?= Yii::$app->formatter->asDate($model->created_at) ?></div>
                        <?php if ($metaUrl): ?>
                            <div class="file-td file-td__name">Экспорт паспорта</div>
                            <div class="file-td file-td__type"><?= mb_strtoupper($meta['extension'] ?? null) ?>, <?= Yii::$app->formatter->asShortSize($meta['size'] ?? null, 0) ?></div>
                            <div class="file-td file-td__control">
                                <a href="<?= $metaUrl ?>" class="btn btn__secondary btn__block-sm">Скачать <i class="material-icons btn-icon btn-icon__right btn-icon__sm">get_app</i></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="content">
                    <div class="table-responsive">
                        <table class="label-table">
                            <tr>
                                <th>Название поля паспорта</th>
                                <th>Значение поля паспорта</th>
                            </tr>
                            <tr>
                                <td>Идентификационный номер</td>
                                <td><?= $model->identifier ?></td>
                            </tr>
                            <tr>
                                <td>Наименование набора данных</td>
                                <td><?= $model->title ?></td>
                            </tr>
                            <tr>
                                <td>Описание набора данных</td>
                                <td><?= $model->description ?></td>
                            </tr>
                            <tr>
                                <td>Владелец набора данных</td>
                                <td><?= $model->owner ?></td>
                            </tr>
                            <tr>
                                <td>Гиперссылки (URL) на страницы сайта</td>
                                <td>
                                    <?php if ($model->urls): ?>
                                        <?php foreach ($model->urls as $url): ?>
                                            <?= Html::a($url, $url) ?><br>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Ответственное лицо</td>
                                <td><?= $model->publishername ?></td>
                            </tr>
                            <tr>
                                <td>Телефон ответственного лица</td>
                                <td><?= $model->publisherphone ?></td>
                            </tr>
                            <tr>
                                <td>Адрес электронной почты ответственного лица</td>
                                <td><?= $model->publishermbox ?></td>
                            </tr>
                            <tr>
                                <td>Гиперссылка (URL) на открытые данные</td>
                                <td><?= Html::a($lastDataUrl, $lastDataUrl) ?></td>
                            </tr>
                            <tr>
                                <td>Формат данных</td>
                                <td><?= mb_strtoupper($meta['extension'] ?? null) ?></td>
                            </tr>
                            <tr>
                                <td>Описание структуры набора открытых данных</td>
                                <td><?= Html::a($lastDataStructureUrl, $lastDataStructureUrl) ?></td>
                            </tr>
                            <?php if ($model->collection && !$model->collection->is_authenticate && $model->collection->alias): ?>
                                <tr>
                                    <td>Доступ к набору через REST API</td>
                                    <td><?= Html::a($model->collection->apiUrl, $model->collection->apiUrl) ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td>Дата первой публикации набора данных</td>
                                <td><?= Yii::$app->formatter->asDate($model->firstData->created_at) ?></td>
                            </tr>
                            <tr>
                                <td>Дата последнего внесения изменений</td>
                                <td><?= Yii::$app->formatter->asDate($model->lastData->created_at) ?></td>
                            </tr>
                            <tr>
                                <td>Содержание последних изменений</td>
                                <td>Обновление данных</td>
                            </tr>
                            <tr>
                                <td>Дата актуальности</td>
                                <td><?= $model->valid ?></td>
                            </tr>
                            <tr>
                                <td>Ключевые слова, соответствующие содержанию набора данных</td>
                                <td><?= $model->keywords ?></td>
                            </tr>
                            <tr>
                                <td>Гиперссылки (URL) на версии открытых данных</td>
                                <td>
                                    <?php foreach ($model->getData()->limit(5)->orderBy(['created_at' => SORT_DESC])->all() as $data): ?>
                                        <?php
                                            $dataUrl = $data->url;
                                            echo Html::a($dataUrl, $dataUrl);
                                        ?>
                                        <br>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Гиперссылки (URL) на версии структуры набора данных</td>
                                <td>
                                    <?php foreach ($model->getStructures()->limit(5)->orderBy(['created_at' => SORT_DESC])->all() as $structure): ?>
                                        <?php
                                            $structureUrl = $structure->url;
                                            echo Html::a($structureUrl, $structureUrl);
                                        ?>
                                        <br>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Версия методических рекомендаций</td>
                                <td><?= Html::a($model->standardversion, $model->standardversion) ?></td>
                            </tr>
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
            <div class="col-third order-xs-0">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
    </div>
</div>

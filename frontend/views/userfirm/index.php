<?php
use yii\bootstrap\ActiveForm;
use yii\web\JsExpression;
use kartik\select2\Select2;

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
                    <h1><?= $page->title ?></h1>

                    <h1><?= $page->content ?></h1>

                    <a class="btn <?=$type=='uk'?'btn__gray':'btn__secondary'?>" href="?type=firm">Муниципальные учреждения</a> <a class="btn <?=$type=='firm'?'btn__gray':'btn__secondary'?>" href="?type=uk">Управляющие организации</a>

                    <div class="boxed form-inside">
                        <?php $form = ActiveForm::begin(['scrollToError' => true]); ?>

                            <?= $form->field($model, 'name')->widget(Select2::class, [
                                'data' => [],
                                'pluginOptions' => [
                                    'minimumInputLength' => 2,
                                    'placeholder' => 'Введите название',
                                    'ajax' => [
                                        'url' => '/userfirm/search?type='.$type,
                                        'dataType' => 'json',
                                        'data' => new JsExpression('function(params) { return {q:params.term};}')
                                    ],
                                ],
                            ]);?>

                            <div class="row">
                                <?php if ($model->type=='firm'){?>
                                <div class="col">
                                    <?= $form->field($model, 'inn')->textInput(['class' => 'form-control']) ?>
                                </div>
                                <?php }else {?>
                                <div class="col">
                                    <?= $form->field($model, 'ogrn')->textInput(['class' => 'form-control']) ?>
                                </div>
                                <?php }?>
                            </div>

                            <div class="form-end">
                                <div class="form-end_right">
                                    <input type="submit" class="btn btn__secondary" value="Найти">
                                </div>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>

                    <?php if (!empty($record)){?>
                        <h3>Найденная организация</h3>
                        <?= frontend\widgets\CollectionRecordWidget::widget([
                            'collectionRecord'=>$record,
                            'renderTemplate'=>false,
                            'columnsAlias'=>[
                                'name',
                                'inn',
                                'orgn',
                                'telefon',
                            ]
                        ]);?>

                        <?php $form = ActiveForm::begin(['scrollToError' => true]); ?>
                            <?= $form->field($model, 'name')->hiddenInput()->label(false) ?>
                            <?= $form->field($model, 'inn')->hiddenInput()->label(false) ?>
                            <button type="submit" class="btn btn__secondary" name="id_record" value="<?=$record->id_record?>">Отправить запрос на редактирование</button>
                        <?php ActiveForm::end(); ?>
                    <?php } else if (!empty($_POST)){?>
                        <h3>Организация не найдена</h3>

                        <p class="accent">Возможно вы неправильно ввели Название или ИНН, или вашей организации нет в нашей базе данных. Вы можете <a href="publishrequest/firm-create?type=<?=$type?>">оставить заявку</a> на добавление вашей организации. После проверки она будет довлена.</p>
                    <?php }?>

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


                    <?php if (!empty($firms)){?>
                        <h3>Привязанные организации</h3>
                        <table>
                            <thead>
                                <th>Организация</th>
                                <th>ИНН</th>
                            </thead>
                            <tbody>
                                <?php foreach ($firms as $id_record => $data)
                                {
                                    echo '<tr><td><a href="publishrequest/firm?id_firm='.$id_record.'">'.$data['name'].'</a></td><td>'.$data['inn'].'</td></tr>';
                                }?>
                            </tbody>
                        </table>
                    <?php }?>
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
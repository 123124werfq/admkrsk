<?php
/* @var common\models\Page $page */

use yii\helpers\Html;
use common\models\CollectionColumn;

?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="" style="width: 100%;">
                <div class="content">
                    <h1>Интерактивное голосование</h1>

                    <?php if(!$data) {?>
                        <p>В данный момент голосование не проводится</p>
                    <?php } else { ?>

                        <p>Период проведения: <?= date('d-m-Y H:i', $data->begin)?> - <?= date('d-m-Y H:i', $data->end)?></p>

                        <p>Эксперт: <?=$profile->name?></p>

                        <form action="" method="POST">
                            <?=Html::hiddenInput(Yii::$app->getRequest()->csrfParam, Yii::$app->getRequest()->getCsrfToken(), []);?>
                            <p>Категории, группы должностей:</p>
                            <table>
                        <?php
                            foreach ($profile->positions as $position)
                            {
                        ?>
                            <tr>
                                <td><?=$position->positionName?></td>
                                <td>
                                    <select name="position[<?=$position->id_profile_position?>]">
                                        <option value="0"></option>
                                        <option value="1" <?=(isset($outvotes[$position->id_profile_position]) && $outvotes[$position->id_profile_position]==1)?'selected':''?>>Включить</option>
                                        <option value="-1" <?=(isset($outvotes[$position->id_profile_position]) && $outvotes[$position->id_profile_position]==-1)?'selected':''?>>Отказать</option>
                                    </select>
                                </td>
                            </tr>
                        <?php
                            }
                        ?>
                            </table>

                            <button class="btn btn__border" id="allPosOn">Включить во все группы должностей</button>
                            <button class="btn btn__border" id="allPosOff">Отказать по всем группам должностей</button>

                            <p>Комментарий</p>
                            <textarea name="comment"></textarea>
                            <div class="right" style="text-align: right;">
                                <input type="reset" class="btn btn__primary" value="Сброс">
                                <input type="submit" class="btn btn__secondary" value="Сохранить и закрыть">
                                <a href="/reserve/vote" class="btn btn__border">Отмена</a>
                            </div>
                        </form>

                    <?php } ?>
                    <hr class="hr hr__md"/>

                    <?php echo frontend\widgets\CollectionRecordWidget::widget(['collectionRecord'=>$collectionRecord]);?>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-2-third">
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

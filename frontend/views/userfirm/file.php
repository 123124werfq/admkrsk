<?php
use yii\bootstrap\ActiveForm;

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
                    <h1>Документв организации</h1>

                    <?=frontend\widgets\FormsWidget::widget(['form'=>$form, 'submitLabel' => 'Добавить']);?>
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
<?php
/* @var common\models\Page $page */
    use yii\helpers\Html;
    use common\models\Collection;

/**
 * @param string $tag
 * @return array
 */
?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-2-third">
                <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
            </div>
        </div>
        <div class="row">
            <div class="col-2-third order-xs-1">
            	<div class="content searchable">
            		<h1><?=$page->title?></h1>
                    <form action="complaint/create" method="get">
                        <div class="form-group">
                            <label class="form-label">Обращение направляется в</label>
                            <div class="custom-select">
                                <?=Html::dropDownList('id_firm','',$firms,['prompt'=>'Выберите организацию','id'=>'Complaint_id_firm','required'=>true])?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Вид</label>
                            <div class="custom-select">
                                <?=Html::dropDownList('id_category','',[],['prompt'=>'Выберите вид','id'=>'Complaint_id_category','required'=>true])?>
                            </div>
                        </div>

                        <div class="form-end">
                            <div class="form-end_right">
                                <input type="submit" class="btn btn__secondary" value="Далее">
                            </div>
                        </div>
                    </form>
            	</div>
            </div>
            <div class="col-third order-xs-0">
            	<?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>

        <hr class="hr hr__md"/>

        <?= $this->render('//site/_pagestat', ['data' => $page])?>

    </div>
</div>

<?=frontend\widgets\AlertWidget::widget(['page'=>$page])?>
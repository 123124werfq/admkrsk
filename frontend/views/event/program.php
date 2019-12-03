<?php use yii\helpers\Html;?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third">
                <h1 class="searchable"><?=Html::encode($collection->name)?></h1>
            </div>
            <div class="col-third">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
        <br/>
        <div class="header-controls">
            <form id="program-filter" action="" method="get">
                <div class="btn-group">
                    <div class="btn-group_item">
                        <div class="datepicker-holder">
                            <input type="text" name="date" class="form-control form-control_datepicker mb-sm-all-0 datepicker-ajax" placeholder="Период мероприятий">
                            <button class="form-control-reset material-icons" type="button">clear</button>
                        </div>
                    </div>
                    <?php if (!empty($districts) && count($districts)>1){?>
                    <div class="btn-group_item">
                        <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                            <?=Html::dropDownList('district','',$districts,['prompt'=>'Район'])?>
                        </div>
                    </div>
                    <?php }?>
                    <?php if (!empty($places) && count($places)>1){?>
                    <div class="btn-group_item">
                        <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                            <?=Html::dropDownList('place','',$places,['prompt'=>'Место'])?>
                        </div>
                    </div>
                    <?php }?>
                    <?php if (!empty($categories) && count($categories)>1){?>
                    <div class="btn-group_item">
                        <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                            <?=Html::dropDownList('category','',$categories,['prompt'=>'Категория мероприятия'])?>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </form>
        </div>
        <!-- <hr class="hr"> -->
        <div class="program-list">
            <?=$this->render('_program-list',['program'=>$program])?>
        </div>
        <!-- на время загрузки аякса добавлять к load-more класс active для анимации -->
        <!--a href="#" class="load-more">
            <span class="load-more_label">Показать ещё</span>
            <span class="load-more_loader">
                <span class="load-more_dot-1"></span>
                <span class="load-more_dot-2"></span>
                <span class="load-more_dot-3"></span>
            </span>
        </a-->
    </div>
</div>
<?php use yii\helpers\Html;?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div>
            <h1>Программа праздника</h1>
            <div class="header-controls">
                <form id="program-filter" action="" method="get">
                    <div class="btn-group">
                        <div class="btn-group_item">
                            <div class="datepicker-holder">
                                <input type="text" class="form-control form-control_datepicker mb-sm-all-0 datepicker-ajax" placeholder="Период мероприятий">
                                <button class="form-control-reset material-icons" type="button">clear</button>
                            </div>
                        </div>
                        <?php if (!empty($districts)){?>
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <?=Html::dropDownList('district','',$districts,['prompt'=>'Район'])?>
                            </div>
                        </div>
                        <?php }?>
                        <?php if (!empty($places)){?>
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <?=Html::dropDownList('place','',$places,['prompt'=>'Место'])?>
                            </div>
                        </div>
                        <?php }?>
                        <?php if (!empty($category)){?>
                        <div class="btn-group_item">
                            <div class="custom-select custom-select__placeholder custom-select__inline ui-front">
                                <?=Html::dropDownList('category','',$category,['prompt'=>'Категория мероприятия'])?>
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
</div>
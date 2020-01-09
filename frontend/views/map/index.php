<?php
    $this->params['page'] = $page;
?>
<div class="main">
    <div class="container">
        <?=frontend\widgets\Breadcrumbs::widget(['page'=>$page])?>
        <div class="row">
            <div class="col-2-third order-xs-1">
                <h1 class="h2">Тест карты1</h1>
                <div class="content">
                    <?=frontend\widgets\YmapWidget::widget(['options' => [], 'points'=>[
                            ['x' => '56.010563', 'y' => '92.852572', 'icon' => '', 'content' => 'Содержимое <em>балуна</em> метки'],
                            ['x' => '56.020563', 'y' => '92.852572', 'icon' => 'islands#redStretchyIcon', 'content' => 'Ещё одна метка'],
                    ]])?>
                </div>
            </div>
            <div class="col-third order-xs-0">
                <?=frontend\widgets\RightMenuWidget::widget(['page'=>$page])?>
            </div>
        </div>
    </div>
</div>
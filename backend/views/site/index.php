<?php

/* @var $this yii\web\View */

$this->title = 'Администрация города Красноярска';
?>
<div class="site-index">

<div class="partitions">

<?php 
    foreach($links as $link)
    {
?>
<div class="partition">
    <a href="<?=$link->link?>"><?=$link->name?></a> <span class="dashboard-unpin" data-id="<?=$link->id_dashboard?>"><i class="fa fa-times"></i></span>  
</div>

<?php
    }
?>
	</div>

</div>
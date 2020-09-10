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
<a href="<?=$link->link?>" class="partition"><?=$link->name?></a>
<?php
    }
?>
	</div>

</div>
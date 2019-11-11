<?php

use common\models\Poll;
use frontend\widgets\PollWidget;
?>

<?= PollWidget::widget(['id_poll' => Poll::getIdRandomActivePool()]) ?>

<?php

use yii\web\View;

/**
 * @var View $this
 * @var boolean $unsub
 */
?>
<div class="main">
    <div class="container">
        <div>
            <?php if ($unsub): ?>
                <h2>Вы успешно отписались от уведомлений!</h2>
            <?php else: ?>
                <h2>К сожалению не удалось отменить рассылку уведомлений!</h2>
            <?php endif; ?>
        </div>
    </div>
</div>
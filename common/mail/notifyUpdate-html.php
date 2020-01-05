<?php

use yii\helpers\Url;
use yii\web\View;

/**
 * @var View $this
 * @var string $message
 * @var int $entityId
 * @var string $linkToEntity
 */

?>
<div>
    <p><?= $message ?></p>
    <p><a href="<?= Url::toRoute([$linkToEntity, 'id' => $entityId], true); ?>">ссылка на изменения.</a></p>
</div>

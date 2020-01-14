<p>
    <strong>
        <a href='<?=$model['url']?>'><?=$model['header']?></a>
    </strong> / <small><?=date("d.m.Y", $model['content_date'])?><?php if(!empty($model['modified_at'])) {?>, обновлено <?=date("d.m.Y", $model['modified_at'])?><?php } ?></small>
        <br>...<?=$model['headline']?>...
</p>
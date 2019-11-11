<div id="alertModal" class="p-5" style="display: none; max-width:600px;">
    <?=$model->content?>

    <center>
        <button data-fancybox-close="" class="btn btn__secondary btn__block-sm">Закрыть</button>
    </center>
</div>

<?php 
$script = <<< JS
    $.fancybox.open({
        src  : '#alertModal',
        type : 'inline'
    });
JS;

$this->registerJs($script, yii\web\View::POS_END);
?>
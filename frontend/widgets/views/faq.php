<?php
/* @var $category \common\models\FaqCategory */
/* @var $faqs \common\models\Faq[] */
?>
<ul class="b-quotes">
    <li class="b-quotes_group">
        <div class="b-quotes_header">
            <h3><?= $category->title ?></h3>
        </div>
    </li>
    <?php foreach ($faqs as $faq): ?>
        <li class="b-quotes_item">
            <div class="b-quotes_header">
                <div class="b-quotes_header_wrap">
                    <?= $faq->question ?>
                </div>
                <div class="b-quotes_header_icon"></div>
            </div>
            <div class="b-quotes_text" style="display:none;">
                <blockquote class="b-quotes_text_wrap usercontent">
                    <?= $faq->answer ?>
                </blockquote>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

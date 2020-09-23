<?php $this->registerJs("var tinymce_plugins = ['".implode(' ',Yii::$app->params['tinymce_plugins'])."'];", yii\web\View::POS_BEGIN);?>
<?=$content?>
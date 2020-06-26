<?php use yii\helpers\Html;?>
<ul class="nav nav-tabs" role="tablist">
    <li>
        <?=Html::a('Данные', ['collection-record/index', 'id' => $model->id_collection], ['class' => 'nav-link'])?>
    </li>
    <li>
        <?=Html::a('Колонки', ['collection-column/index', 'id' => $model->id_collection], ['class' => 'nav-link'])?>
    </li>
    <li>
        <?=Html::a('Главная форма', ['form/view', 'id' => $model->id_form], ['class' => 'nav-link'])?>
    </li>
    <li>
        <?=Html::a('Формы', ['form/collection', 'id' => $model->id_collection], ['class' => 'nav-link'])?>
    </li>
    <li <?=Yii::$app->request->pathInfo=='collection/pages'?'class="active"':''?>>
        <?=Html::a('Страницы', ['collection/pages', 'id' => $model->id_collection], ['class' => 'nav-link'])?>
    </li>
</ul>
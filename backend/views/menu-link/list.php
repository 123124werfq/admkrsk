<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Меню';
$this->params['breadcrumbs'][] = $this->title;
if (Yii::$app->user->can('admin.menu')) {
    $this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
}
?>
<div class="row">
    <div class="col-sm-7">
        <div class="ibox">
            <div class="ibox-content">
                <ul>
                <?php foreach ($dataProvider->query->all(); as $key => $parent)
                {
                    echo '<li class="menu-item">'.$parent->label.;
                    foreach ($parent->childs as $ckey => $childs) {
                        # code...
                    }
                    echo "</li>";
                }?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-5">
    </div>
</div>
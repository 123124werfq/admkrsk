<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Рубрики муниципальных услуг';
$this->params['breadcrumbs'][] = $this->title;

$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);
?>

<div class="row">
    <div class="col-sm-7">
        <ul data-id="null" class="menu-container">
        <?php foreach ($records as $key => $parent)
        {
            echo $this->render('_row',['data'=>$parent]);
        }
        ?>
        </ul>
    </div>
    <div class="col-sm-5">
    </div>
</div>
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Жизненные ситуации';
$this->params['breadcrumbs'][] = $this->title;
$this->params['button-block'][] = Html::a('Добавить', ['create'], ['class' => 'btn btn-success']);

?>

<div class="row">
    <div class="col-sm-7">
        <ul data-id="null" class="menu-container" data-model="ServiceSituation">
        <?php foreach ($records as $key => $parent)
        {
            echo '<li data-id="'.$parent->id_situation.'"" class="menu-item">
                    '.$this->render('_row',['data'=>$parent]).'
                    <ul data-id="'.$parent->id_situation.'"" class="menu-childs">';

            foreach ($parent->childs as $ckey => $child) {
                echo '<li data-id="'.$child->id_situation.'" class="menu-item">
                            '.$this->render('_row',['data'=>$child]).'
                            <ul data-id="'.$child->id_situation.'"" class="menu-childs"></ul>
                      </li>';
            }
            echo "</ul></li>";
        }?>
        </ul>
    </div>
    <div class="col-sm-5">
    </div>
</div>



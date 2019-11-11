<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Меню';
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['menu/index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['menu/update','id'=>$model->id_menu]];

if (Yii::$app->user->can('admin.menu')) {
    $this->params['button-block'][] = Html::a('Добавить', ['create','id_menu'=>$model->id_menu], ['class' => 'btn btn-success']);
}
?>
<div class="row">
    <div class="col-sm-7">
        <ul data-id="null" class="menu-container">
        <?php foreach ($records as $key => $parent)
        {
            echo '<li data-id="'.$parent->id_link.'"" class="menu-item">
                    <div>
                        <a href="#">'.$parent->label.'</a>
                        <div class="button-column">
                            <a href="create?id='.$parent->id_link.'&id_menu='.$model->id_menu.'" title="Добавить" aria-label="Добавить"><span class="glyphicon glyphicon-plus"></span></a>
                            <a href="update?id='.$parent->id_link.'" title="Редактировать" aria-label="Редактировать"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a href="delete?id='.$parent->id_link.'" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
                        </div>
                    </div>
                    <ul data-id="'.$parent->id_link.'"" class="menu-childs">';

            foreach ($parent->childs as $ckey => $child) {
                echo '<li data-id="'.$child->id_link.'" class="menu-item">
                            <div>
                                <a href="#">'.$child->label.'</a>
                                <div class="button-column">
                                    <a class="create-menu" href="create?id='.$parent->id_link.'&id_menu='.$model->id_menu.'" title="Добавить" aria-label="Добавить"><span class="glyphicon glyphicon-plus"></span></a>
                                    <a href="update?id='.$child->id_link.'" title="Редактировать" aria-label="Редактировать"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <a href="delete?id='.$child->id_link.'" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
                                </div>
                            </div>
                            <ul data-id="'.$child->id_link.'"" class="menu-childs"></ul>
                      </li>';
            }
            echo "</ul></li>";
        }?>
        </ul>
    </div>
    <div class="col-sm-5">
    </div>
</div>
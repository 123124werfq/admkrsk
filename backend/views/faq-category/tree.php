<?php

use common\models\FaqCategory;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $children FaqCategory[] */

$this->title = 'Дерево категорий';
$this->params['breadcrumbs'][] = ['label' => $children[0]->breadcrumbsLabel, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

if (Yii::$app->user->can('admin.faq')) {
    $this->params['button-block'][] = Html::a('Добавить категорию', ['create'], ['class' => 'btn btn-success']);
}
?>

<?php foreach ($children as $key => $child): ?>
    <div class="treerow col-sm-offset-<?= $child->depth - 1 ?>">
        <div class="row">
            <div class="col-sm-10">
                <?= $child->title ?>
            </div>
            <div class="col-sm-2 text-right">
                <div class="button-column">
                    <a href="create?id_faq_category=<?=$child->id_faq_category?>" title="Добавить" aria-label="Добавить"><span class="glyphicon glyphicon-plus"></span></a>
                    <a href="update?id=<?=$child->id_faq_category?>" title="Редактировать" aria-label="Редактировать"><span class="glyphicon glyphicon-pencil"></span></a>
                    <a href="delete?id=<?=$child->id_faq_category?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

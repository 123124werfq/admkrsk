<?php

use backend\assets\GridAsset;
use backend\controllers\UserController;
use common\models\GridSetting;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $customColumns array */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
//$this->params['button-block'][] = Html::a('о', ['create'], ['class' => 'btn btn-success']);

GridAsset::register($this);

$defaultColumns = [
    'id' => 'id',
    'username' => [
        'attribute' => 'username',
        'format' => 'raw',
        'value' => function (User $model) {
            $badge = "";
            $desc = "";
            if (!empty($model->id_ad_user)) {
                $desc = $model->getAdinfo()->one()->description;
                if (empty($desc)) {
                    $desc = $model->getAdinfo()->one()->company;
                }
                $badge .= ' <span class="badge badge-warning">AD</span>';
            }
            if (!empty($model->id_esia_user)) {
                $badge .= ' <span class="badge badge-primary">ЕСИА</span>';
            }

            if (!empty($model->id_ad_user) && !empty($model->id_esia_user)) {
                $badge .= ' <em>связан с пользователем ' . $model->esiainfo->fullname . '</em>';
            }

            $badge .= '<br/><small>' . $desc . '</small>';

            return $model->getUsername() . $badge;
        },
    ],
    'email' => 'email:email',
    'status' => [
        'attribute' => 'status',
        'format' => 'raw',
        'value' => function (User $model) {
            return $model->statusName;
        },
        'filter' => User::getStatusNames(),
    ],
    'roles' => [
        'attribute' => 'roles',
        'format' => 'raw',
        'value' => function (User $model) {
            return implode('<br>', ArrayHelper::map(Yii::$app->authManager->getRolesByUser($model->id), 'name', 'description'));
        },
    ],
];
list($gridColumns, $visibleColumns) = GridSetting::getGridColumns(
    $defaultColumns,
    $customColumns,
    User::class
);
?>
<div id="accordion">
    <h3 id="grid-setting">Настройки таблицы</h3>
    <div id="sortable">
        <?php foreach ($visibleColumns as $name => $isVisible): ?>
            <div class="ui-state-default">
                <input type="checkbox" <?= $isVisible ? 'checked' : null ?> />
                <span><?= $name ?></span></div>
        <?php endforeach; ?>
        <div class="ibox">
            <div style="
            padding-top: 5px;
            padding-left: 10px;">
                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'id' => 'sb']) ?>
            </div>
        </div>
    </div>
</div>

<div class="ibox">
    <div class="ibox-content">
        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>
    <div class="ibox">
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 10]) ?>"><button class="btn btn-primary">10</button></a>
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 20]) ?>"><button class="btn btn-primary">20</button></a>
        <a style="color: white" href="<?= Url::to(['', 'pageSize' => 40]) ?>"><button class="btn btn-primary">40</button></a>
    </div>
</div>

<div class="ibox">
    <div class="ibox-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => array_merge(array_values($gridColumns), [
                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions' => ['class' => 'button-column'],
                    'template' => '{view} {action} {update} {delete}',
                    'buttons' => [
                        'action' => function ($url, $model, $key) {
                            return Html::a('<span class="fa fa-history"></span>', $url, ['title' => 'Действия']);
                        },
                    ],
                ]
            ]),
            'tableOptions' => [
                'emptyCell ' => '',
                'class' => 'table table-striped ids-style valign-middle table-hover',
                'data-grid' => UserController::grid,
                'id' => 'grid',
            ]
        ]); ?>
    </div>
</div>
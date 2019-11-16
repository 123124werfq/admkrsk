<?php

use common\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $searchModel->breadcrumbsLabel;
$this->params['breadcrumbs'][] = $this->title;
//$this->params['button-block'][] = Html::a('о', ['create'], ['class' => 'btn btn-success']);

?>
<div class="ibox">
    <div class="ibox-content">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </div>        
</div>
<div class="ibox">
    <div class="ibox-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'columns' => [
                'id',
                //'username',
                [
                    'attribute' => 'username',
                    'format' => 'raw',
                    'value' => function (User $model) {
                        $badge = "";
                        if(!empty($model->id_ad_user)) {
                            $desc = $model->getAdinfo()->one()->description;
                            if(empty($desc))
                                $desc = $model->getAdinfo()->one()->company;
                            $badge = ' <span class="badge badge-warning">AD</span><br/><small>' . $desc . '</small>';
                        }
                        else if(!empty($model->id_esia_user))
                            $badge = ' <span class="badge badge-primary">ЕСИА</span>';



                        return $model->getUsername().$badge;
                    },
                ],
                //'auth_key',
                'email:email',
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'value' => function (User $model) {
                        return $model->statusName;
                    },
                    'filter' => User::getStatusNames(),
                ],
                //'created_at',
                //'updated_at',
                //'verification_token',
                [
                    'attribute' => 'roles',
                    'format' => 'raw',
                    'value' => function (User $model) {
                        return implode('<br>', ArrayHelper::map(Yii::$app->authManager->getRolesByUser($model->id), 'name', 'description'));
                    },
                ],
                //'id_esia_user',
                //'id_ad_user',
                //'fullname',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'contentOptions'=>['class'=>'button-column'],
                    'template' => '{view} {action} {update} {delete}',
                    'buttons' => [
                        'action' => function ($url, $model, $key) {
                            return Html::a('<span class="fa fa-history"></span>', $url, ['title' => 'Действия']);
                        },
                    ],
                ],
            ],
            'tableOptions'=>[
                'emptyCell '=>'',
                'class'=>'table table-striped ids-style valign-middle table-hover'
            ]
        ]); ?>
    </div>
</div>
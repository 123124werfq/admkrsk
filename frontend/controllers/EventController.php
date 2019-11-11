<?php

namespace frontend\controllers;
use common\models\Project;
use common\models\Page;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use Yii;

class EventController extends \yii\web\Controller
{
    public function actionIndex($page=null)
    {
        $projects = Project::find();//->where('date_end >='.time().' OR date_end IS NULL')

        if (!empty(Yii::$app->request->get('type')))
            $projects->andWhere(['type'=>Yii::$app->request->get('type')]);

        $projects = $projects->all();

        if (empty($page))
            $page = Page::find()->where(['alias'=>'events'])->one();

        if (empty($page))
            throw new NotFoundHttpException('');

        return $this->render('index',[
            'page'=>$page,
            'projects'=>$projects,
        ]);
    }
}

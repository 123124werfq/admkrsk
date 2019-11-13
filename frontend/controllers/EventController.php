<?php

namespace frontend\controllers;
use common\models\Action;
use common\models\Project;
use common\models\Page;
use common\models\Collection;
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
            $page = Page::findOne(['alias'=>'events']);

        if (empty($page))
            throw new NotFoundHttpException('');

        $page->createAction();

        return $this->render('index',[
            'page'=>$page,
            'projects'=>$projects,
        ]);
    }

    public function actionProgram($id,$id_page)
    {
        $collection = Collection::findOne($id);
        $collection = $collection->getData([],true);

        $program = [];
        foreach ($collection as $key => $data)
        {
            $date = (is_string($data['date']))?strtotime($data['date']):$data['date'];
            $program[$date][$key] = $data;
        }

        $page = Page::findOne($id_page);

        return $this->render('program',['page'=>$page,'program'=>$program]);
    }
}

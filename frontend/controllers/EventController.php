<?php

namespace frontend\controllers;
use common\models\Action;
use common\models\Project;
use common\models\Page;
use common\models\District;
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

        if (empty($collection))
            throw new NotFoundHttpException('');

        $collection = $collection->getDataQuery()->select();
        $collection->keyAsAlias = true;

        if (!empty($_GET['district']))
        {
            $collection->whereByAlias(['district'=>$_GET['district']]);
        }

        if (!empty($_GET['place']))
            $collection->whereByAlias(['place'=>$_GET['place']]);

        if (!empty($_GET['category']))
            $collection->whereByAlias(['category'=>$_GET['category']]);

        $collection = $collection->getArray();

        $program = [];

        $allDistricts = District::find()->select(['name','id_district'])->indexBy('id_district')->all();

        $districts = [];
        $places = [];
        $categories = [];

        foreach ($collection as $key => $data)
        {   
            if (!isset($data['date']))
                return false;

            if (!empty($data['district']) && isset($allDistricts[$data['district']]))
                $districts[$data['district']] = $allDistricts[$data['district']]->name;

            if (!empty($data['place']))
                $places[$data['place']] = $data['place'];

            if (!empty($data['category']))
                $categories[$data['category']] = $data['category'];

            $date = (is_string($data['date']))?strtotime($data['date']):$data['date'];
            
            $program[$date][$key] = $data;
        }

        if (Yii::$app->request->isAjax)
        {
            return $this->renderPartial('_program-list',[
                'program'=>$program,
            ]);
        }

        $page = Page::findOne($id_page);

        return $this->render('program',[
            'page'=>$page,
            'program'=>$program,
            'districts'=>$districts,
            'places'=>$places,
            'categories'=>$categories,
        ]);
    }
}

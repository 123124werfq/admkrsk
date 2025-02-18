<?php

namespace frontend\controllers;

use common\models\Project;
use common\models\Page;
use common\models\District;
use common\models\Collection;
use yii\base\InvalidConfigException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use Yii;

class EventController extends Controller
{
    /**
     * @param null $page
     * @return string
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionIndex($page=null)
    {
        $projects = Project::find();//->where('date_end >='.time().' OR date_end IS NULL')

        if (!empty(Yii::$app->request->get('type')))
            $projects->andWhere(['type'=>Yii::$app->request->get('type')]);

        $date = Yii::$app->request->get('date');

         // фильтр даты
        if (!empty($date))
        {
            $dates = explode('-', $date);

            if (count($dates)==2)
            {
                $date_begin = strtotime(trim($dates[0]));
                $date_begin = mktime(0,0,0,date('m',$date_begin),date('d',$date_begin),date('Y',$date_begin));

                $date_end = strtotime(trim($dates[1]));
                $date_end = mktime(24,59,59,date('m',$date_end),date('d',$date_end),date('Y',$date_end));

                $projects->andWhere(['>=','date_begin',$date_begin]);
                $projects->andWhere(['<=','date_end',$date_end]);
            }
        }

        $projects = $projects->all();

        if (empty($page))
            $page = Page::findOne(['alias'=>'events']);

        if (empty($page))
            throw new NotFoundHttpException('');

        $page->createAction();

        return $this->render('index',[
            'page'=>$page,
            'date'=>$date,
            'projects'=>$projects,
        ]);
    }

    /**
     * @param $id
     * @param $id_page
     * @return string
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionProgram($id,$id_page)
    {
        $model = Collection::findOne($id);

        if (empty($model))
            throw new NotFoundHttpException('');

        $collection = $model->getDataQuery()->select();
        $collection->keyAsAlias = true;
        $collection->orderByAlias(['date'=>SORT_ASC]);

        if (!empty($_GET['date']))
        {
            $dates = explode('-', $_GET['date']);

            if (count($dates)==2)
            {
                $date_begin = strtotime(trim($dates[0]));
                $date_begin = mktime(0,0,0,date('m',$date_begin),date('d',$date_begin),date('Y',$date_begin));

                $date_end = strtotime(trim($dates[1]));
                $date_end = mktime(23,59,59,date('m',$date_end),date('d',$date_end),date('Y',$date_end));

                $collection->whereByAlias(['>=','date',$date_begin]);
                $collection->whereByAlias(['<=','date',$date_end]);
            }
        }

        if (!empty($_GET['district']))
        {
            $collection->whereByAlias(['district'=>$_GET['district']]);
        }

        if (!empty($_GET['place']))
        {
            $collection->whereByAlias(['place'=>$_GET['place']]);
        }

        if (!empty($_GET['category']))
            $collection->whereByAlias(['category'=>$_GET['category']]);

        $collection = $collection->getArray();

        $program = [];

        $allDistricts = District::find()->select(['name','id_district'])->indexBy('id_district')->orderBy('name')->all();

        $districts = [0=>'Все районы'];
        $places = [];
        $categories = [];

        foreach ($collection as $key => $data)
        {
            if (!isset($data['date']))
                $data['date'] = 0;

            if (!empty($data['district']) && isset($allDistricts[$data['district']]))
                $districts[$data['district']] = $allDistricts[$data['district']]->name;

            if (!empty($data['place']))
                $places[$data['place']] = $data['place'];

            if (!empty($data['category']))
                $categories[$data['category']] = $data['category'];

            if (!isset($data['time']) && isset($data['group']) && $data['date'])
            {
                $time = [];

                if (!empty($data['date']))
                    $time[] = date('d.m.Y',(int)$data['date']);

                if (!empty($data['date_end']))
                    $time[] = date('d.m.Y',(int)$data['date_end']);

                $data['time'] = implode('-', $time);

                if (!empty($data['date_time']))
                    $data['time'] .= '<br>'.$data['date_time'];
            }

            $date = (is_numeric($data['date']))?strftime('%e %B (%A)',(int)$data['date']):$data['date'];
            $program[(!empty($data['group']))?$data['group']:$date][$key] = $data;
        }

        asort($places);
        asort($categories);

        array_unshift($places,'Все места');
        array_unshift($categories,'Любая категория');

        if (Yii::$app->request->isAjax)
        {
            return $this->renderPartial('_program-list',[
                'program'=>$program,
            ]);
        }

        $page = Page::findOne($id_page);

        if (empty($page))
            throw new NotFoundHttpException('');

        return $this->render('program',[
            'page'=>$page,
            'program'=>$program,
            'districts'=>$districts,
            'places'=>$places,
            'categories'=>$categories,
            'collection'=>$model,
        ]);
    }
}

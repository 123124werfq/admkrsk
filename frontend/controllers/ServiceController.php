<?php

namespace frontend\controllers;

use common\models\Service;
use common\models\Form;
use common\models\Workflow;
use common\models\Integration;
use common\models\Collection;
use common\models\ServiceAppeal;
use common\models\ServiceAppealState;
use common\models\ServiceRubric;
use common\models\FormDynamic;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\widgets\ActiveForm;
use yii\web\Response;

class ServiceController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($page=null)
    {
        return $this->render('/site/blocks',['page'=>$page]);
    }

    public function actionReestr($page=null,$id_situation=null)
    {
        $clientType = Yii::$app->request->get('client_type');
        $firm = (int)Yii::$app->request->get('firm');
        $online = (int)Yii::$app->request->get('online');

        $services = Service::find()->where(['old'=>0]);

        $open = false;

        $collection = Collection::find()->where(['alias'=>'service_offices'])->one();

        $firms = [];

        if (!empty($collection))
        {
            $records = $collection->getData([],true);

            foreach ($records as $key => $record)
            {
                if (empty($record['service_firm']) || !is_array($record['service_firm']))
                    continue;

                $id_firm = key($record['service_firm']);

                $firms[$id_firm] = $record['service_firm'][$id_firm];
            }
        }

        asort($firms);
        if (!empty($clientType) && in_array($clientType, Service::getAttributeValues('client_type')))
        {
            $open = true;
            $services->andWhere(['@>','client_type','{'.$clientType.'}']);
        }

        if (!empty($online))
        {
            $open = true;
            $services->andWhere(['online'=>1]);
        }

        if (!empty($firm) && !empty($records))
        {
            $open = true;

            $id_records = [];

            foreach ($records as $key => $record)
            {
                if (isset($record['service_firm'][$firm]))
                    $id_records[] = $key;
            }

            if (!empty($id_records))
                $services->andWhere('id_service IN (SELECT id_service FROM servicel_collection_firm WHERE id_record IN ('.implode(',', $id_records).'))');
        }

        if (!empty($id_situation) && !$open)
        {
            $id_situation = (int)$id_situation;
            $open = true;
            $services->andWhere("id_service IN (SELECT id_service FROM servicel_situation WHERE id_situation = $id_situation)");
        }

        $servicesRubs = [];

        foreach ($services->all() as $key => $data)
            $servicesRubs[(int)$data->id_rub][$data->id_service] = $data;

        $rubrics = ServiceRubric::find()->with(['childs','parent'])->where(['id_rub'=>array_keys($servicesRubs)])->all();

        $tree = [];

        foreach ($rubrics as $key => $rub)
        {
            $tree[(int)$rub->id_parent][(int)$rub->id_rub] = $rub;

            if (!empty($rub->id_parent))
            {
                $tree[(int)$rub->parent->id_parent][(int)$rub->id_parent] = $rub->parent;

                if (!empty($rub->parent->parent))
                {
                    $tree[(int)$rub->parent->parent->id_parent][(int)$rub->parent->parent->id_parent] = $rub->parent->parent;
                }
            }
        }

        foreach ($tree as $key => &$array)
        {
            if (is_array($array))
            {
                uasort($array, function ($a, $b)
                {
                    if ($a->ord == $b->ord)
                        return 0;

                    return ($a->ord < $b->ord)?-1:1;
                });
            }
        }

        if (Yii::$app->request->isAjax)
        {
            return $this->renderPartial('_reestr',['rubrics'=>$tree,'servicesRubs'=>$servicesRubs,'active'=>($open)?'active':'']);
        }

        return $this->render('reestr',[
            'page'=>$page,
            'servicesRubs'=>$servicesRubs,
            'rubrics'=>$tree,
            'open'=>$open,
            'firms'=>$firms,
        ]);
    }

    public function actionForms($page)
    {
        $models = Service::find()->with([
            'forms',
            'rubric'
        ])->where([
            'old'=>0,
            'show_forms'=>1,
            'online'=>1
        ])->all();

        $services = [];

        foreach ($models as $key => $service)
        {
            $services[$service->id_rub][$service->id_service] = $service;
        }

        $rubs = ServiceRubric::find()->where(['id_rub'=>array_keys($services)])->all();

        /*$parents = [];
        foreach ($rubs as $key => $rub)
        {
            if (!empty($rub->id_parent))
            {
                if (!empty($rub->parent->id_parent))
                {

                }
            }
            else
                $parents[] = $rub->id_rub;
        }*/

        return $this->render('forms',[
            'rubs'=>$rubs,
            'page'=>$page,
            'services'=>$services,
        ]);
    }

    /**
     * @param $id
     * @param null $page
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id,$page=null)
    {
    	$model = $this->findModel($id);

        $model->createAction();

        return $this->render('view',[
        	'service'=>$model,
            'page'=>$page,
        ]);
    }

    public function actionCategory($id_firm)
    {
        $query = ServiceAppealForm::find()->with('category as category')->select(['id_category','id_firm','category.lineValue as category_name'])->where(['id_firm'=>$id_firm])->groupBy('id_category');

        $results = [];
        foreach ($query->asArray()->all() as $category)
        {
            $results[] = [
                'id' => $category['id_category'],
                'text' => $category['category_name'],
            ];
        }

        return ['results' => $results];
    }

    protected function processAIS($data)
    {
        //var_dump($data);
        //die();

        $lname = urlencode($data[8196]??'');
        $fname = urlencode($data[8197]??'');
        $mname = urlencode($data[8198]??'');

        $serie = $data[11978]??'0000';
        $serie = urlencode($serie[0].$serie[1]." ".$serie[2].$serie[3]);
        $num = urlencode($data[11979]??'');

        $request = "http://10.24.0.195:700/Service.svc/GetQueueNumber?lname=$lname&fname=$fname&mname=$mname&docseries=$serie&docnumber=$num";

        /*
        echo $request;
        $res = file_get_contents($request);

        var_dump($res);
        */
        $res = @json_decode(file_get_contents($request));

        if(isset($res->Data))
            return $res->Data;
        else
            return false;
    }

    /**
     * @param $id_form
     * @param null $page
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCreate($id_form,$page=null)
    {
        $inputs = [];

        $form  = $this->findForm($id_form);
        $service = $this->findModel($form->id_service);

        $inputs['id_service'] = $service->id_service;
        $inputs['id_form'] = $id_form;

        $model = new FormDynamic($form);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $prepare = $model->prepareData(true);

            if ($id_form == 666) // хардкод для теста
            {
                $aisres = $this->processAIS($prepare);

                Yii::$app->response->format = Response::FORMAT_JSON;

                return [
                    'success'=>($aisres)?"Ваш номер в очереди: $aisres":"Информация об очередности не обнаружена"
                ];
            }

            if ($record = $form->collection->insertRecord($prepare))
            {
                $insertedData = $record->getData(true);

                $appeal = new ServiceAppeal;
                $appeal->id_user = Yii::$app->user->id;
                $appeal->id_service = $service->id_service;
                $appeal->id_record = $record->id_record;
                //$appeal->id_collection = $form->collection->id_collection;
                $appeal->date = time();
                $appeal->id_target = $insertedData['id_target'];
                $appeal->state = 'empty'; // это переехало в ServiceAppealState, убрать в перспективе
                $appeal->created_at = time();

                $idents = [
                   'guid' => Service::generateGUID()
                ];

                $appeal->data = json_encode($idents);

                if ($appeal->save())
                {
                   $state = new ServiceAppealState;
                   $state->id_appeal = $appeal->id_appeal;
                   $state->date = time();
                   $state->state = (string)ServiceAppealState::STATE_INIT;

                   if ($state->save())
                   {
                       $appeal->state = $state->state;
                       $appeal->number_internal = $appeal->id_appeal; // пока такой внутренний номер
                       $appeal->updateAttributes(['state', 'number_internal']);

                        // запрос к СЭД
                        $attachments = $record->getAllMedias();

                        $export_path = $record->collection->form->makeDoc($record, ['number_internal' => (string)$appeal->number_internal, 'date' => date("d.m.Y", $appeal->date)]);

                        $wf = new Workflow;
                        $archivePath = $wf->generateArchive($idents['guid'], $attachments, $export_path);
                        // ... тут XML
                        if($archivePath)
                            $wf->xopCreate($archivePath);

                        $rawResult = $wf->sendServiceMultipartMessage($archivePath);
                        
                        //echo $rawResult;
                        //die();
                        
                        //$opres = $wf->sendServiceMessage($appeal);

                        $opres = strpos($rawResult, "INVALID");


                        $integration = new Integration;
                        $integration->system = Integration::SYSTEM_SED;
                        $integration->direction = Integration::DIRECTION_OUTPUT;
                        if($opres <= 0)
                            $integration->status = Integration::STATUS_OK;
                        else
                            $integration->status = Integration::STATUS_ERROR;
                        

                        $integration->description = ' Запрос услуги ' . $appeal->number_internal;

                        $integration->data = json_encode([
                            'appeal' => $appeal->number_internal ?? null,
                            'user' => $appeal->id_user ?? null,
                            'target' => $appeal->id_target ?? null,
                            'record' => $appeal->record ?? null,
                            'rawResponse' => $rawResult
                        ]);

                        $integration->created_at = time();
                        $integration->save();

                        Yii::$app->response->format = Response::FORMAT_JSON;

                        return [
                            'success'=>$form->message_success?$form->renderMessage($record,[
                                    'appeal_number'=> isset($appeal->number_internal)?$appeal->number_internal:false,
                                    'service_reestr_number'=>$service->reestr_number,
                                    'service_reestr_name'=>$service->name,
                                    'service_date' => date("d.m.Y", $appeal->created_at),
                                    'service_fio' => Yii::$app->user->identity->username
                                ]):$this->renderPartial('_result',[
                                'number'=> isset($appeal->number_internal)?$appeal->number_internal:false,
                                'service'=>$service,
                                'page' => $page,
                                'date' => date("d.m.Y", $appeal->created_at),
                                'fio' => Yii::$app->user->identity->username
                            ])
                        ];
                   }
                }
                else
                {
                   var_dump($appeal->errors);
                   die();
                }
            }
        }
        elseif (Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }


        return $this->render('create',[
            'form'=>$form,
            'service'=>$service,
            'page'=>$page,
            'inputs'=>$inputs,
        ]);
    }

    public function actionSearch()
    {
        $search = Yii::$app->request->get('term');

        if (empty($search))
            Yii::$app->end();

        $records = Service::find()->where(['like','name',$search])->limit(10)->all();

        $output = [];

        foreach ($records as $data)
            $output[] = ['id'=>$data->id_service, 'value'=>$data->name, 'label'=>$data->name, 'description'=>'', 'redirect'=>$data->getUrl()];

        return json_encode($output);
    }

    public function actionUserhistory($page=null)
    {
        $appeals = ServiceAppeal::find()->where(['id_user' => Yii::$app->user->id])->orderBy('id_appeal DESC')->all();

        return $this->render('userhistory', [
            'page' => $page,
            'appeals' => $appeals
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такой страницы не существует');
    }

    protected function findForm($id)
    {
        if (($model = Form::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такой страницы не существует');
    }


}

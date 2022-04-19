<?php

namespace frontend\controllers;

use Yii;
use common\models\ServiceComplaintForm;
use common\models\CollectionRecord;
use common\models\FormDynamic;
use yii\web\NotFoundHttpException;

use common\models\Page;
use common\models\ServiceAppeal;
use common\models\ServiceAppealState;
use common\models\Service;
use common\models\ServiceCounter;
use common\models\Workflow;
use common\models\Integration;
use yii\web\Response;
use yii\widgets\ActiveForm;


class ComplaintController extends \yii\web\Controller
{
    public function actionIndex($page=null)
    {
        $firms = ServiceComplaintForm::find()
            ->where('id_form IS NOT NULL')
            ->groupBy('id_record_firm')
            ->select('id_record_firm')
            ->indexBy('id_record_firm')
            ->asArray()
            ->all();

        $ids = array_keys($firms);

        $records = CollectionRecord::find()->where(['id_record'=>$ids])->all();

        $firms = [];

        foreach ($records as $key => $record)
            $firms[$record->id_record] = $record->getLineValue();

        if (empty($firms))
            throw new NotFoundHttpException('Не заполнен справочник организаций');

        return $this->render('index',[
            'firms'=>$firms,
            'page'=>$page,
        ]);
    }

    public function actionCreateOld($id_firm, $id_category, $page=null)
    {
    	$form = ServiceComplaintForm::find()
    				->with(['form'])
    				->where(['id_record_firm'=>$id_firm,'id_record_category'=>$id_category])
    				->one();

    	if (empty($form) || empty($form->form))
    		throw new NotFoundHttpException('Такой страницы не существует');

    	$form = $form->form;

    	$collection = $form->collection;

        $model = new FormDynamic($form);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $prepare = $model->prepareData(true);

            if ($collection->insertRecord($prepare))
            {
                echo "OK!";

                if (!empty($form->url))
                	return $this->redirect($form->url);

                if (!empty($form->id_page) && $url = Page::getUrlByID($form->id_page))
                	return $this->redirect($url);

                return $this->redirect($form->message_success);
            }
            else
                echo "Данные не сохранены";
        }

    	return $this->render('create',[
    		'form'=>$form,
    		'page'=>$page,
    	]);
    }

    public function actionCreate($id_firm, $id_category, $page=null)
    {
    	$form = ServiceComplaintForm::find()
    				->with(['form'])
    				->where(['id_record_firm'=>$id_firm,'id_record_category'=>$id_category])
    				->one();

    	if (empty($form) || empty($form->form))
    		throw new NotFoundHttpException('Такой страницы не существует');

    	$form = $form->form;
        $service = $form->service;

    	$collection = $form->collection;

        $model = new FormDynamic($form);

        $inputs = [];
        $inputs['id_service'] = $service->id_service;
        $inputs['id_form'] = $form->id_form;        

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $prepare = $model->prepareData(true);

            if ($record = $collection->insertRecord($prepare))
            {
                /*
                echo "OK!";

                if (!empty($form->url))
                	return $this->redirect($form->url);

                if (!empty($form->id_page) && $url = Page::getUrlByID($form->id_page))
                	return $this->redirect($url);

                return $this->redirect($form->message_success);
                */

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
                $appeal->data = json_encode($insertedData);

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
                        $appeal->number_internal = substr(strtoupper(str_replace('-','',$idents['guid'])),0,32); // пока такой внутренний номер

                        $numberCounter = ServiceCounter::find()->where(['service_number' => $appeal->target->reestr_number])->one();
                        if(!$numberCounter)
                        {
                            $numberCounter = new ServiceCounter;
                            $numberCounter->service_number = $appeal->target->reestr_number;
                            $numberCounter->value = 0;
                            $numberCounter->save();
                        }

                        $servCounter = $numberCounter->value + 1;

                        $numberCounter->value = $servCounter;
                        $numberCounter->updateAttributes(['value']);

                        $appeal->number_common = ($appeal->target->reestr_number . '-' . $servCounter);
                        //$appeal->number_system = $idents['guid'];
                        $appeal->updateAttributes(['state', 'number_internal', 'number_common']);

                        // запрос к СЭД
                        $attachments = $record->getAllMedias();

                        $export_path = $record->collection->form->makeDoc($record, ['number_internal' => (string)$appeal->number_internal, 'date' => date("d.m.Y", $appeal->date)]);

                        $wf = new Workflow;
                        $archivePath = $wf->generateArchive($idents['guid'], $attachments, $export_path);

                        $esiaUser = Yii::$app->user->identity->esiainfo;

                        if($esiaUser)
                        {
                            $insertedData['snils'] = $esiaUser->snils;
                            $insertedData['passport_serie'] = $esiaUser->passport_serie;
                            $insertedData['passport_number'] = $esiaUser->passport_number;
                            $insertedData['passport_date'] = $esiaUser->passport_date;
                            $insertedData['passport_issuer'] = $esiaUser->passport_issuer;
                            $insertedData['mobile'] = $esiaUser->mobile;   
                            
                            $eparts = explode("@", Yii::$app->user->identity->email);
                            
                            $insertedData['esiaid'] = "esia#".$eparts[0]."@gosuslugi.ru";

                            if(isset($insertedData['municService']) && isset($insertedData['num']))
                            {
                                $insertedData['municService'] = $insertedData['num'];
                            }
                        }

                        $insertedData['is_fl'] = true;

                        if(isset($insertedData["category"]) && $insertedData["category"] != "Физическое лицо") 
                            $insertedData['is_fl'] = false;


                        //var_dump($appeal->target->form->fullname); 
                        //var_dump($appeal->service->subject); // описание (шаблон)
                        //var_dump($insertedData); // все данные из формы
                        //$esiaUser = EsiaUser::find()->where(['id_user' => Yii::$app->user->id])->one();
                        //var_dump($esiaUser->snils);
                        //die();

                        // ... тут XML
                        if($archivePath)
                            $toSend = $wf->xopCreate($archivePath, $appeal, $insertedData);

                        //echo($toSend);

                        if($toSend)
                            $rawResult = $wf->sendServiceMultipartMessage($toSend);

                        //echo $rawResult;
                        //die();

                        //$opres = $wf->sendServiceMessage($appeal);

                        $opres = strpos($rawResult, "ACCEPT");


                        $integration = new Integration;
                        $integration->system = Integration::SYSTEM_SED;
                        $integration->direction = Integration::DIRECTION_OUTPUT;
                        if($opres > 0)
                            $integration->status = Integration::STATUS_OK;
                        else
                            $integration->status = Integration::STATUS_ERROR;


                        $integration->description = ' Обжалование ' . $appeal->number_internal;

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
                                    'appeal_id' => $appeal->id_appeal,
                                    'appeal_number'=> isset($appeal->number_internal)?$appeal->number_internal:false,
                                    'service_reestr_number'=>$service->reestr_number,
                                    'service_reestr_name'=>$service->name,
                                    'service_date' => date("d.m.Y", $appeal->created_at),
                                    'service_fio' => Yii::$app->user->identity->username
                                ]):$this->renderPartial('_result',[
                                    'number'=> isset($appeal->number_internal)?$appeal->number_internal:false,
                                    'number_common' => $appeal->number_common,
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
            elseif (Yii::$app->request->isAjax)
            {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }            
            else
            {
                echo "Данные не сохранены";
            }
        }

    	return $this->render('create',[
    		'form'=>$form,
    		'page'=>$page,
    	]);
    }    
}
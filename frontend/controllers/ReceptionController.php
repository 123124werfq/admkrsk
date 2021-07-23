<?php

namespace frontend\controllers;

use common\models\AppealRequest;
use common\models\AppealState;
use common\models\CollectionRecord;
use common\models\CollectionColumn;
//use frontend\modules\api\models\CollectionRecord;
use Yii;
use common\models\Page;
use common\models\Collection;
use common\models\FormDynamic;
use common\models\Form;
use common\models\HrProfile;

use yii\web\BadRequestHttpException;
use yii\widgets\ActiveForm;
use yii\web\Response;

class ReceptionController extends \yii\web\Controller
{
    public function actionRequest()
    {
        echo "request"; die();
        return $this->render('index');
    }


    public function actionIndex($page=null)
    {
        $collection = Collection::findOne(['alias'=>'appeals']);

        if(!$collection || !$page)
            throw new BadRequestHttpException();

        $model = new FormDynamic($collection->form);

        if ($model->load(Yii::$app->request->post()))
        {
            if ($model->validate())
            {
                $prepare = $model->prepareData(true);

                if ($record = $collection->insertRecord($prepare))
                {
                    $insertedData = $record->getData(true);

                    $appeal = new AppealRequest;

                    if(Yii::$app->user->isGuest)
                        $appeal->is_anonimus = 1;
                    else
                        $appeal->id_user = Yii::$app->user->id;

                    $appeal->id_record = $record->id_record;

                    if ($appeal->save())
                    {
                        $state = new AppealState;
                        $state->id_request = $appeal->id_request;

                        $state->state = (string)AppealState::STATE_INIT;

                        if ($state->save())
                        {
                            $appeal->number_internal = "ВП-".date("Y")."-".str_pad($appeal->id_request, 6, '0', STR_PAD_LEFT);
                            $appeal->updateAttributes(['state', 'number_internal']);

                            // запрос к СЭД
                            /*
                            $attachments = $record->getAllMedias();

                            $export_path = $record->collection->form->makeDoc($record);

                            $wf = new Workflow;
                            $wf->generateArchive($idents['guid'], $attachments, $export_path);

                            // ... тут XML
                            $opres = $wf->sendServiceMessage($appeal);
                            $integration = new Integration;
                            $integration->system = Integration::SYSTEM_SED;
                            $integration->direction = Integration::DIRECTION_OUTPUT;
                            if($opres)
                                $integration->status = Integration::STATUS_OK;
                            else
                                $integration->status = Integration::STATUS_ERROR;

                            $integration->description = ' Запрос услуги ' . $appeal->number_internal;

                            $integration->data = json_encode([
                                'appeal' => $appeal->number_internal ?? null,
                                'user' => $appeal->id_user ?? null,
                                'target' => $appeal->id_target ?? null,
                                'record' => $appeal->record ?? null
                            ]);

                            $integration->created_at = time();
                            $integration->save();
                            */

                            Yii::$app->response->format = Response::FORMAT_JSON;
                            return [
                                'success'=>$this->renderPartial('result', [
                                    'page' => $page,
                                    'fio' => $insertedData['surname'] . " " . $insertedData['name'] . " " . $insertedData['parental_name'],
                                    'number' => $appeal->number_internal,
                                    'date' => date( "d.m.Y", $appeal->created_at)
                                ])
                            ];
                        }
                    }
                    else
                    {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [
                            'error'=>"Ошибка отправки, пожалуйста повторите попытку позднее",
                        ];
                    }
                }
            }
            else
            {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
        }

        return $this->render('index', [
            'form'      => $collection->form,
            'page'      => $page,
        ]);

    }

    public function actionForm()
    {
        return $this->render('form');
    }

    public function actionCreate()
    {
        die();
        return $this->render('result');
    }
}
<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\FirmUser;
use common\models\Collection;
use common\models\FormDynamic;
use common\models\Form;
use common\models\CollectionRecord;
use frontend\models\UserFirmForm;
use yii\web\NotFoundHttpException;

use yii\filters\AccessControl;
use yii\web\Response;

class UserfirmController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionSearch($q)
    {
        $collection = $this->getCollection();

        $records = $collection->getDataQuery()
            ->whereByAlias(['like','name',$q])
            ->getArray(true);

        $results = [];
        foreach ($records as $id_record=>$data) {
            $results[] = [
                'id' => $data['name'],
                'text' => $data['name'],
            ];
        }

        return $this->asJson(['results' => $results]);
    }

    public function actionIndex($page = null)
    {
        $model = new UserFirmForm;

        $record = null;

        $collection = $this->getCollection();

        if ($model->load(Yii::$app->request->post()))
        {

            //$collection = Collection::find()->where(['alias'=>'municipal_firms'])->one();

            if (!empty($collection))
            {
                $record = $collection->getDataQuery()
                    ->whereByAlias(['inn'=>(int)$model->inn])
                    ->whereByAlias(['name'=>$model->name])
                    ->limit(1)->getArray();

                if (!empty($record))
                    $record = CollectionRecord::findOne(key($record));

                $id_record = Yii::$app->request->post('id_record');

                if (!empty($id_record) && $id_record==$record->id_record)
                {
                    $FirmUser = FirmUser::find()->where(['id_record'=>$record->id_record])->one();

                    if (empty($FirmUser))
                    {
                        $FirmUser = new FirmUser;
                        $FirmUser->id_user = Yii::$app->user->id;
                        $FirmUser->state = 0;
                        $FirmUser->id_record = $id_record;

                        if ($FirmUser->save())
                            return $this->redirect([$page->getUrl().'/firm','id_firm'=>$FirmUser->id_record]);
                    }
                    else
                        $model->addError('name','Данная организация уже привязана к пользователю');
                }
            }
        }

        $firms = $this->getFirms();

        $ids = [];
        foreach ($firms as $key => $data)
            $ids[] = $data['id_record'];

        $firms = $collection->getDataQuery()
                    ->andWhere(['id_record'=>$ids])
                    ->getArray(true);

        return $this->render('index', [
            'page' => $page,
            'record'=>$record,
            'model' => $model,
            'firms' => $firms,
        ]);
    }

    public function actionFile($page,$id_firm)
    {
        $firm = $this->getFirm($id_firm);

        if (empty($firm))
            throw new NotFoundHttpException('The requested page does not exist.');

        $form = null;

        if ($firm->state == $firm::STATE_ACCEPT)
        {
            $collection = Collection::find()->where(['alias'=>'firm_documents'])->one();

            $form = Form::find()->where(['alias'=>'firm_documents_user_form'])->one();

            if (!empty($form))
            {
                $modelForm = new FormDynamic($form);

                if ($modelForm->load(Yii::$app->request->post()))
                {
                    if ($modelForm->validate())
                    {
                        $prepare = $modelForm->prepareData(true);

                        $prepare['id_firm'] = $firm->id_record;

                        if ($collection->insertRecord($prepare))
                        {
                            if (Yii::$app->request->isAjax)
                            {
                                Yii::$app->response->format = Response::FORMAT_JSON;

                                return [
                                    'success'=>$form->message_success?$form->message_success:'Спасибо, данные отправлены'
                                ];
                            }

                            return $this->redirect('');
                        }
                    }
                    else
                    {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ActiveForm::validate($model);
                    }

                }
            }
        }

        $files = $collection->getDataQuery()->whereByAlias(['id_firm'=>$firm->id_record])->getArray(true);

        return $this->render('file', [
            'page' => $page,
            'firm'=>$firm,
            'form'=>$form,
            'files'=>$files,
            'record'=>$firm->record,
        ]);
    }

    public function actionFirm($page,$id_firm)
    {
        $firm = $this->getFirm($id_firm);

        $form = null;

        if ($firm->state == $firm::STATE_ACCEPT)
        {
            $collection = Collection::find()->where(['alias'=>'municipal_firms'])->one();

            $form = Form::find()->where(['alias'=>'municipal_firms_user_form'])->one();

            if (!empty($form))
            {
                $modelForm = new FormDynamic($form);

                if ($modelForm->load(Yii::$app->request->post()))
                {
                    if ($modelForm->validate())
                    {
                        $prepare = $modelForm->prepareData(true);
                        $record = $collection->updateRecord($firm->id_record, $prepare);

                        if (Yii::$app->request->isAjax)
                        {
                            Yii::$app->response->format = Response::FORMAT_JSON;

                            return [
                                'success'=>$form->message_success?$form->message_success:'Спасибо, данные отправлены'
                            ];
                        }
                    }
                    else
                    {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return ActiveForm::validate($model);
                    }
                }
            }
        }

        return $this->render('firm', [
            'page' => $page,
            'firm'=>$firm,
            'form'=>$form,
            'record'=>$firm->record,
        ]);
    }

    protected function getCollection()
    {
        $collection = Collection::find()->where(['alias'=>'municipal_firms'])->one();

        if (empty($collection))
            throw new NotFoundHttpException();

        return $collection;
    }

    protected function getFirms()
    {
        $firms = FirmUser::find()->where(['id_user'=>Yii::$app->user->id])->all();

        return $firms;
    }

    protected function getFirm($id_firm)
    {
        $firm = FirmUser::find()->where(['id_user'=>Yii::$app->user->id,'id_record'=>$id_firm])->one();

        if (empty($firm))
            throw new NotFoundHttpException();

        return $firm;
    }
}

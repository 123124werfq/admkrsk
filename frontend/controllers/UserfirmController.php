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


    public function actionIndex($page = null)
    {
        $firm = $this->getFirm();

        if (!empty($firm))
            return $this->redirect($page->getUrl().'/firm');

        $model = new UserFirmForm;

        $record = null;

        if ($model->load(Yii::$app->request->post()))
        {
            $collection = Collection::find()->where(['alias'=>'municipal_firms'])->one();

            if (!empty($collection))
            {
                $record = $collection->getDataQuery()
                    //->whereByAlias(['name'=>$model->name])
                    ->whereByAlias(['inn'=>(int)$model->inn])->limit(1)->getArray();

                if (!empty($record))
                    $record = CollectionRecord::findOne(key($record));

                $id_record = Yii::$app->request->post('id_record');

                if (!empty($id_record) && $id_record==$record->id_record)
                {
                    $FirmUser = new FirmUser;
                    $FirmUser->id_user = Yii::$app->user->id;
                    $FirmUser->state = 0;
                    $FirmUser->id_record = $id_record;

                    if ($FirmUser->save())
                        return $this->redirect($page->getUrl().'/firm');
                }
            }
        }

        return $this->render('index', [
            'page' => $page,
            'record'=>$record,
            'model' => $model,
        ]);
    }

    public function actionFirm($page)
    {
        $firm = $this->getFirm();

        if (empty($firm))
            throw new NotFoundHttpException('The requested page does not exist.');

        $form = null;

        if ($firm->state == $firm::STATE_ACCEPT)
        {
            $collection = Collection::find()->where(['alias'=>'municipal_firms'])->one();

            $form = Form::find()->where(['alias'=>'municipal_firms_user_form'])->one();

            if (!empty($form))
            {
                $modelForm = new FormDynamic($form);

                if ($modelForm->load(Yii::$app->request->post()) && $modelForm->validate())
                {
                    $prepare = $modelForm->prepareData(true);
                    $record = $collection->updateRecord($firm->id_record, $prepare);

                    $this->redirect('');
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

    protected function getFirm()
    {
        $firm = FirmUser::find()->where(['id_user'=>Yii::$app->user->id])->one();

        return $firm;
    }
}

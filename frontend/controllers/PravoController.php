<?php

namespace frontend\controllers;

use Yii;
use common\models\Pdocument;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class PravoController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout = 'pravo';

        $docs = Pdocument::find()->where(['deleted_at' => null])->orderBy('regdate DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $docs,
            'pagination' => [ 'pageSize' => 30 ],
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }


    public function actionDetail()
    {
        $regnum = Yii::$app->request->get('regnum');

        $doc = Pdocument::find()->where(['regnum' => $regnum])->one();

        if(!$doc)
            throw new NotFoundHttpException();

        return $this->render('detail', ['data' => $doc]);

    }


}

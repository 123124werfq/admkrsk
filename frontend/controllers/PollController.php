<?php

namespace frontend\controllers;

use common\models\Page;
use common\models\Poll;
use frontend\models\PollForm;
use frontend\models\search\PollSearch;
use Yii;
use yii\web\NotFoundHttpException;

class PollController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new PollSearch();
        $dataProvider = $searchModel->search([]);

        if (($page = Page::findOne(['alias' => 'poll-list-active'])) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page' => $page,
        ]);
    }

    public function actionArchive()
    {
        $searchModel = new PollSearch(['archive' => true]);
        $dataProvider = $searchModel->search([]);

        if (($page = Page::findOne(['alias' => 'poll-archive'])) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page' => $page,
        ]);
    }

    public function actionView($id)
    {
    	$model = Poll::findOne($id);

    	if (empty($model)) {
    		throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (($page = Page::findOne(['alias' => $model->isExpired() ? 'poll-archive' : 'poll-list-active'])) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

    	$form = new PollForm(['id_poll' => $model->id_poll]);

    	if ($form->load(Yii::$app->request->post()) && $form->save()) {
    	    $this->redirect(['view', 'id' => $model->id_poll]);
        }

        return $this->render('view',[
        	'model'=>$model,
            'page' => $page,
        ]);
    }
}

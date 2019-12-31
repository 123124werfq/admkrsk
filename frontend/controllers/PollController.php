<?php

namespace frontend\controllers;

use common\models\Page;
use common\models\Poll;
use frontend\models\PollForm;
use frontend\models\search\PollSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class PollController extends Controller
{
    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new PollSearch();
        $dataProvider = $searchModel->search([]);

        if (($page = Page::findOne(['alias' => 'poll-list-active'])) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $page->logUserAction();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page' => $page,
        ]);
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionArchive()
    {
        $searchModel = new PollSearch(['archive' => true]);
        $dataProvider = $searchModel->search([]);

        if (($page = Page::findOne(['alias' => 'poll-archive'])) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $page->logUserAction();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page' => $page,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     */
    public function actionView($id)
    {
        $poll = Poll::findOne($id);

        if (empty($poll)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (($page = Page::findOne(['alias' => $poll->isExpired() ? 'poll-archive' : 'poll-list-active'])) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $pollForm = new PollForm($poll);

        if (Yii::$app->request->isPost) {
            $pollForm->load(Yii::$app->request->post());

            if ($pollForm->save()) {
                $this->redirect(['view', 'id' => $poll->id_poll]);
            }
        }

        $poll->logUserAction();

        return $this->render('view', [
            'pollForm' => $pollForm,
            'poll' => $poll,
            'page' => $page,
        ]);
    }
}

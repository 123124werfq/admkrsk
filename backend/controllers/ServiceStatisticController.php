<?php

namespace backend\controllers;

use common\models\Action;
use common\models\AuthEntity;
use common\modules\log\models\Log;
use Yii;
use common\models\ServiceSituation;
use backend\models\search\ServiceStatisticSearch;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ServiceSituationController implements the CRUD actions for ServiceSituation model.
 */
class ServiceStatisticController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['backend.service.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => ServiceStatistic::class,
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ServiceSituation models.
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionIndex()
    {
        $servicesSent = new ServiceStatisticSearch();
        $dataProvider = $servicesSent->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $servicesSent,
            'dataProvider' => $dataProvider,
        ]);

    }
}

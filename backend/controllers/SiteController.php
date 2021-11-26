<?php

namespace backend\controllers;

use Yii;
use yii\base\Exception;
use yii\web\{Controller,Cookie,Response};
use yii\filters\{VerbFilter,AccessControl};
use common\models\{Action,LoginForm,AdUser,Dashboard};

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'setting', 'savelink', 'deletelink', 'test'],
                        'allow' => true,
                        'roles' => ['backend'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dashboardLinks = Dashboard::find()->where(['id_user' => Yii::$app->user->id])->all();

        return $this->render('index', ['links' => $dashboardLinks]);
    }

    /**
     * Save settings.
     *
     * @return array
     */
    public function actionSetting()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $result = false;

        if (($mininavbar = Yii::$app->request->post('mininavbar')) !== null) {
            Yii::$app->response->cookies->add(new Cookie(['name' => 'mininavbar', 'value' => $mininavbar]));
            $result = true;
        }

        return ['result' => $result];
    }

    /**
     * Login action.
     *
     * @return string
     * @throws Exception
     */
    public function actionLogin()
    {
        $this->layout = 'login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if (!Yii::$app->user->identity->can('backend')) {
                $backend = Yii::$app->authManager->getPermission('backend');
                Yii::$app->authManager->assign($backend, Yii::$app->user->identity->id);
            }

            Yii::$app->user->identity->createAction(Action::ACTION_LOGIN);
            return $this->goBack();
        } else if ($model->load(Yii::$app->request->post()) && AdUser::adlogin($model->username, $model->password)) {
            if (!Yii::$app->user->identity->can('backend')) {
                $backend = Yii::$app->authManager->getPermission('backend');
                Yii::$app->authManager->assign($backend, Yii::$app->user->identity->id);
            }

            Yii::$app->user->identity->createAction(Action::ACTION_LOGIN_AD);
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * @throws Exception
     */
    public function actionRecord()
    {
        $pos = intval(Yii::$app->request->post('pos')) + 1;
        $modelId = intval(Yii::$app->request->post('id'));
        $modelPk = Yii::$app->request->post('pk');
        $table = Yii::$app->request->post('table');

        $where = Yii::$app->request->post('where');
        $where = !empty($where) ? $where : '';
        $additionalWhere = (!empty($where) ? " AND $where" : '');

        $sql = "SELECT ord FROM $table WHERE `$modelPk` = '$modelId'" . $additionalWhere;
        $currentOrd = (int)Yii::$app->db->createCommand($sql)->queryScalar();

        $sql = "UPDATE $table SET ord = ord-1 WHERE ord > $currentOrd" . $additionalWhere;
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE $table SET ord = ord+1 WHERE ord >= $pos" . $additionalWhere;
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE $table SET ord = $pos WHERE `$modelPk` = '$modelId'" . $additionalWhere;
        Yii::$app->db->createCommand($sql)->execute();
    }

    public function actionSavelink()
    {
        if(!isset($_POST['name']) || !isset($_POST['url']) )
            die();

        $user = Yii::$app->user->identity;

        $dasboardLink = Dashboard::find()->where(['id_user' => $user->id])->andWhere(['link' => $_POST['url']])->one();

        if(!$dasboardLink)
            $dasboardLink = new Dashboard;

        $dasboardLink->id_user = $user->id;
        $dasboardLink->name = $_POST['name'];
        $dasboardLink->link = $_POST['url'];

        $dasboardLink->save();
    }

    public function actionDeletelink()
    {
        if(!isset($_POST['id']) )
            die();

        $user = Yii::$app->user->identity;

        $dasboardLink = Dashboard::find()->where(['id_user' => $user->id])->andWhere(['id_dashboard' => $_POST['id']])->one();

        if($dasboardLink)
            $dasboardLink->delete();
    }

    public function actionTest()
    {
        /*echo "<pre>";
        print_r([
            'remoteIp' => Yii::$app->request->getRemoteIP(),
            'userIp' => Yii::$app->request->getUserIP(),
            '_SERVER' => $_SERVER,
        ]);*/

        $model = new \common\models\CollectionRecordNew();
        $model->collectionName = 'test';

        die();
    }
    
}

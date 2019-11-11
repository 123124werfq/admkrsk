<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\AdUser;
use yii\web\Cookie;
use yii\web\Response;


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
                        'actions' => ['index', 'setting'],
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
        return $this->render('index');
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
     */
    public function actionLogin()
    {
        $this->layout = 'login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else if($model->load(Yii::$app->request->post()) && AdUser::adlogin($model->username, $model->password)) {
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

     public function actionReord()
    {
        $pos        = (int)$_POST['pos']+1;
        $id_model   = (int)$_POST['id'];
        $pk_model   = $_POST['pk'];
        $table      = $_POST['table'];
        $where      = (isset($_POST['where']))?$_POST['where']:'';

        $sql = "SELECT ord FROM $table WHERE `$pk_model` = '$id_model'".((!empty($where))?" AND $where":'');
        $current_ord = (int)Yii::$app->db->createCommand($sql)->queryScalar();

        $sql = "UPDATE $table SET ord = ord-1 WHERE ord > $current_ord".((!empty($where))?" AND $where":'');
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE $table SET ord = ord+1 WHERE ord >= $pos".((!empty($where))?" AND $where":'');
        Yii::$app->db->createCommand($sql)->execute();

        $sql = "UPDATE $table SET ord = $pos WHERE `$pk_model` = '$id_model'".((!empty($where))?" AND $where":'');
        Yii::$app->db->createCommand($sql)->execute();
    }
}

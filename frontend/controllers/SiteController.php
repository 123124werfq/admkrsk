<?php

namespace frontend\controllers;

use common\models\EsiaFirm;
use Esia\Config;
use Esia\Exceptions\InvalidConfigurationException;
use Esia\OpenId;
use Esia\Signer\CliSignerPKCS7;
use Esia\Signer\Exceptions\SignFailException;
use frontend\components\ApiErrorHandler;
use common\models\Action;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use PhpOffice\PhpWord\IOFactory;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\ContentNegotiator;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Page;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\User;
use common\models\EsiaUser;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use common\models\Workflow;
use common\models\Smev;
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
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
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
            'captcha' => [
                //'class' => 'yii\captcha\CaptchaAction',
                'class' => 'common\components\captcha\NumericCaptcha',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'ssoCallback'],
            ],

        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionError()
    {
        $route = explode('/', Yii::$app->requestedRoute);

        if ($route[0] == 'api') {
            $this->attachBehavior('contentNegotiator', [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ]);
            $this->negotiate();

            if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
                $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
            }

            $errorHandler = new ApiErrorHandler();
            $errorHandler->handleException($exception);
        }

        return (new ErrorAction('error', $this))->run();
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     * @throws InvalidConfigurationException
     * @throws SignFailException
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->user->identity->createAction(Action::ACTION_LOGIN);
            return $this->goBack();
        } else {
            $model->password = '';

            // получаем УРЛ для входа через ЕСИА
            if (!file_exists(Yii::getAlias('@app') . '/assets/admkrsk2021.pem')) {
                return $this->goBack();
            }

            $esia = User::openId();
            $esia->setSigner(new CliSignerPKCS7(
                Yii::getAlias('@app'). '/assets/admkrsk2021.pem',
                Yii::getAlias('@app'). '/assets/admkrsk2021.pem',
                'CdtDblGfh1',//'T%52gs]CPJ',
                Yii::getAlias('@runtime')
            ));

            Yii::$app->session->set('backUrl', empty($_SESSION['backUrlExpert'])?Yii::$app->request->referrer:$_SESSION['backUrlExpert']);
            unset($_SESSION['backUrlExpert']);

            $url = $esia->buildUrl();

            return $this->render('login', [
                'model' => $model,
                'esiaurl' => $url
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        if(Yii::$app->user->isGuest)
            return $this->goHome();

        $user = User::findOne(Yii::$app->user->id);
        Yii::$app->user->logout();

        if (!empty($user->id_esia_user))
        {
            $esia = User::openId();
            $logoutUrl = $esia->buildLogoutUrl(Yii::$app->homeUrl);

            $this->redirect($logoutUrl);
        }

        Yii::$app->end();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @param null $page
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionPage($page = null)
    {
        if (empty($page))
        {
            $url = Yii::$app->request->url;
            $alias = explode('/', $url);
            $alias = array_pop($alias);

            if (strpos($alias, '?') > 0)
                $alias = substr($alias, 0, strpos($alias, '?'));

            if (empty($alias))
                $alias = '/';

            $page = Page::findOne(['alias' => $alias]);
        }

        if (empty($page))
            throw new NotFoundHttpException();

        $blocks = $page->blocks;

        if (!$page->active && !empty($page->hidden_message))
            return $this->render('page_hidden',[
                        'page'=>$page
                   ]);
        else if (!$page->active)
            throw new NotFoundHttpException();

        $page->createAction();

        return $this->render((empty($blocks))?'page':'blocks',[
            'page'=>$page
        ]);
    }

    public function actionFlush()
    {
        Yii::$app->cache->flush();
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    public function ssoCallback($client)
    {
        $attributes = $client->getUserAttributes();
        $oid = $attributes['oid'];

        $user = User::findByOid($oid);
        if ($user) {
            $esiauser = EsiaUser::findOne($user->id_esia_user);

            if ($esiauser)
                $esiauser->actualize($client);

            $login = Yii::$app->user->login($user);
            Yii::$app->user->identity->createAction(Action::ACTION_LOGIN_ESIA);

            return $login;
        }

        $personInfo = $client->getPersonInfo($oid);

        $user = new User();
        $user->email = $oid . '@esia.ru';
        $user->username = $personInfo['firstName'] . ' ' . $personInfo['lastName'];
        $user->setPassword($personInfo['eTag']);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;

        $esiauser = new EsiaUser();

        if ($esiauser->actualize($client))
            $user->id_esia_user = $esiauser->id_esia_user;

//        $user->first_name = $personInfo['firstName'];
//        $user->last_name = $personInfo['lastName'];
//        $user->middle_name = $personInfo['middleName'];

        if (!$user->save()) {
            throw new yii\web\ServerErrorHttpException('Внутренняя ошибка сервера');
        }

        Yii::$app->user->login($user);
        Yii::$app->user->identity->createAction(Action::ACTION_SIGNUP_ESIA);

        return $this->redirect('/');
    }

    public function actionTestad1()
    {
        $un = 'web_user';
        $ldapObject = \Yii::$app->ad->search()->findBy('sAMAccountname', $un);
        var_dump($ldapObject);
        echo '<pre>' . print_r($ldapObject, true) . '</pre>';
    }

    public function actionTestad1a()
    {
        $un = 'akatov';
        $ldapObject = \Yii::$app->ad->search()->findBy('sAMAccountname', $un);
        $givenName = $ldapObject['givenname'][0];
        $surname = $ldapObject['sn'][0];
        echo $givenName . " " . $surname . " " . $ldapObject['department'][0] . " " . $ldapObject['description'][0] . " ";
        var_dump(implode(unpack('C*', $ldapObject['objectsid'][0])));
    }

    public function actionTestad2()
    {

        $ldaprdn = 'web_user@admkrsk.ru';     // ldap rdn или dn
        $ldappass = 'PaO5q#3ows';  // ассоциированный пароль

        // соединение с сервером
        $ldapconn = ldap_connect("10.24.0.7")
        or die("Не могу соединиться с сервером LDAP.");

        if ($ldapconn) {

            // привязка к ldap-серверу
            $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

            // проверка привязки
            if ($ldapbind) {
                echo "LDAP-привязка успешна...";
            } else {
                echo "LDAP-привязка не удалась...";
            }

        }
    }

    public function actionTestad3()
    {
        set_time_limit(30);
        error_reporting(E_ALL);
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);

// config
        $ldapserver = '10.24.0.7';
        $ldapuser = 'web_user@admkrsk.ru';
        $ldappass = 'PaO5q#3ows';
        $ldaptree = "DC=admkrsk,DC=ru";

// connect
        $ldapconn = ldap_connect($ldapserver) or die("Could not connect to LDAP server.");

        if ($ldapconn) {
            // binding to ldap server
            $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass) or die ("Error trying to bind: " . ldap_error($ldapconn));
            // verify binding
            if ($ldapbind) {
                echo "LDAP bind successful...<br /><br />";


                $result = ldap_search($ldapconn, $ldaptree, "(cn=*)") or die ("Error in search query: " . ldap_error($ldapconn));
                $data = ldap_get_entries($ldapconn, $result);

                // SHOW ALL DATA
                echo '<h1>Dump all data</h1><pre>';
                print_r($data);
                echo '</pre>';


                // iterate over array and print data for each entry
                echo '<h1>Show me the users</h1>';
                for ($i = 0; $i < $data["count"]; $i++) {
                    //echo "dn is: ". $data[$i]["dn"] ."<br />";
                    echo "User: " . $data[$i]["cn"][0] . "<br />";
                    if (isset($data[$i]["mail"][0])) {
                        echo "Email: " . $data[$i]["mail"][0] . "<br /><br />";
                    } else {
                        echo "Email: None<br /><br />";
                    }
                }
                // print number of entries found
                echo "Number of entries found: " . ldap_count_entries($ldapconn, $result);
            } else {
                echo "LDAP bind failed...";
            }

        }

// all done? clean up
        ldap_close($ldapconn);
    }

    public function actionTestad4()
    {
        $person = "web_user";
        $dn = "DC=admkrsk,DC=ru";
        $filter = "(|(sn=$person*)(givenname=$person*))";
        $justthese = array("ou", "sn", "givenname", "mail");

        $ds = ldap_connect('10.24.0.7');
        ldap_bind($ds, 'web_user@admkrsk.ru', 'PaO5q#3ows');

        $sr = ldap_search($ds, $dn, $filter, $justthese);

        $info = ldap_get_entries($ds, $sr);

        echo $info["count"] . " записей возвращено\n";
    }

    public function actionTestad5()
    {
        $person = "web_user@admkrsk.ru";
        $dn = "DC=admkrsk,DC=ru";
        $filter = "(mail=$person)";
        $justthese = array("ou", "sn", "givenname", "mail");

        $ds = ldap_connect('10.24.0.7');
        ldap_bind($ds, 'web_user@admkrsk.ru', 'PaO5q#3ows');

        $sr = ldap_search($ds, $dn, $filter, $justthese);

        $info = ldap_get_entries($ds, $sr);

        var_dump($info);

        //echo $info["count"]." записей возвращено\n";
    }

    public function actionTcurl()
    {
        $ch = curl_init(); // create cURL handle (ch)
        if (!$ch) {
            die("Couldn't initialize a cURL handle");
        }
// set some cURL options
        $ret = curl_setopt($ch, CURLOPT_URL, "http://ya.ru");
        $ret = curl_setopt($ch, CURLOPT_HEADER, 1);
        $ret = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        $ret = curl_setopt($ch, CURLOPT_TIMEOUT, 30);

// execute
        $ret = curl_exec($ch);

        if (empty($ret)) {
            // some kind of an error happened
            die(curl_error($ch));
            curl_close($ch); // close cURL handler
        } else {
            $info = curl_getinfo($ch);
            curl_close($ch); // close cURL handler

            if (empty($info['http_code'])) {
                die("No HTTP code was returned");
            } else {
                // load the HTTP codes
                $http_codes = parse_ini_file("path/to/the/ini/file/I/pasted/above");

                // echo results
                echo "The server responded: <br />";
                echo $info['http_code'] . " " . $http_codes[$info['http_code']];
            }

        }
    }


    public function actionWtest()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        $section = $phpWord->addSection();

        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
            . 'The important thing is not to stop questioning." '
            . '(Albert Einstein)'
        );

        $section->addText(
            '"Great achievement is usually born of great sacrifice, '
            . 'and is never the result of selfishness." '
            . '(Napoleon Hill)',
            array('name' => 'Tahoma', 'size' => 10)
        );

        $fontStyleName = 'oneUserDefinedStyle';
        $phpWord->addFontStyle(
            $fontStyleName,
            array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
        );
        $section->addText(
            '"The greatest accomplishment is not in never falling, '
            . 'but in rising again after you fall." '
            . '(Vince Lombardi)',
            $fontStyleName
        );

        $html = "<h1>ВСЕМ ПРИВЕТ!</h1><p>Это абзац</p><table><tr><td>А</td><td>Это</td><td>Таблица</td></tr></table>";

        //$section2 = $phpWord->addSection();
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html);

        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Tahoma');
        $fontStyle->setSize(13);
        $myTextElement = $section3->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
        $myTextElement->setFontStyle($fontStyle);

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('../runtime/helloWorld.docx');
    }

    public function actionStest()
    {
        $ww = new Workflow;
        //$res = $ww->makeSign('/var/tmp/p7s/test1.docx');
        //echo $res;
        $ww->sendMultipartTest();
        die();
    }

    public function actionMsql()
    {

        //$sql = 'EXEC SMEV.get_QueueNumber @lname=:Param1,@fname=:Param2,@mname=:Param3,@docseries=:Param4,@docnumber=:Param5';
        //$command = Yii::$app->aisDb->createCommand();
        $lname = urlencode('малиновская');
        $fname = urlencode('лилия');
        $mname = urlencode('юрьевна');
        $serie = urlencode('04 03');
        $num = urlencode('866821');

        $request = "http://10.24.0.195:700/Service.svc/GetQueueNumber?lname=$lname&fname=$fname&mname=$mname&docseries=$serie&docnumber=$num";

        //$command->bindParam(':Param1', $lname);
        //$command->bindParam(':Param2', $fname);
        //$command->bindParam(':Param3', $mname);
        //$command->bindParam(':Param4', $serie);
        //$command->bindParam(':Param5', $num);

        //$res = $command->queryRow(); //queryRow()

        $res = @json_decode(file_get_contents($request));

        var_dump($res);
        die();
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionFakelogin()
    {
        if (true || YII_ENV_DEV) {
            $user = User::findOne(8);

            Yii::$app->user->login($user, 3600 * 24 * 7);

            $this->redirect("/");
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionFakelogin2()
    {
        if (true || YII_ENV_DEV) {
            $user = User::findOne(2406);

            Yii::$app->user->login($user, 3600 * 24 * 7);

            $this->redirect("/");
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionEsiatest()
    {
        //var_dump(Yii::getAlias('@app'). '/assets/admkrsk.pem'); die();

        if (!file_exists(Yii::getAlias('@app') . '/assets/admkrsk2021.pem')) {
            echo "no cert";
            die();
        }


        $config = new Config([
            'clientId' => '236403241',
            'privateKeyPath' => Yii::getAlias('@app') . '/assets/admkrsk2021.pem',
            'certPath' => Yii::getAlias('@app') . '/assets/admkrsk2021.pem',
            'redirectUrl' => 'https://t1.admkrsk.ru/site/signin',
            'portalUrl' => 'https://esia.gosuslugi.ru/',
            'scope' => ['fullname', 'birthdate', 'mobile', 'contacts', 'snils', 'inn', 'id_doc', 'birthplace', 'medical_doc', 'residence_doc', 'email', 'usr_org', 'usr_avt'],
        ]);
        $esia = new OpenId($config);
        $esia->setSigner(new CliSignerPKCS7(
            Yii::getAlias('@app') . '/assets/admkrsk2021.pem',
            Yii::getAlias('@app') . '/assets/admkrsk2021.pem',
            'CdtDblGfh1',//'T%52gs]CPJ',
            Yii::getAlias('@runtime')
        ));

        $url = $esia->buildUrl();

        echo "<a href=$url>логин через ESIA</a>";

        echo urlencode($url);
        Yii::$app->end();
    }

    public function actionSignin()
    {
        //var_dump($_REQUEST); die();
        if (!isset($_REQUEST['code'])) {
            return $this->goHome();
            //var_dump($_REQUEST);
            //die();
        }

        $esia = User::openId();
        $esia->setSigner(new CliSignerPKCS7(
            Yii::getAlias('@app'). '/assets/admkrsk2021.pem',
            Yii::getAlias('@app'). '/assets/admkrsk2021.pem',
            'CdtDblGfh1',//'T%52gs]CPJ',
            Yii::getAlias('@runtime')
        ));

        $token = $esia->getToken($_REQUEST['code']);

        // блок для юзера - физика

        $personInfo = $esia->getPersonInfo();
        //$addressInfo = $esia->getAddressInfo();
        //$contactInfo = $esia->getContactInfo();
        //$documentInfo = $esia->getDocInfo();

        $oid = $esia->getConfig()->getOid();

        $roles = $esia->getRolesInfo();

        $backUrl = Yii::$app->session->get('backUrl', '/');

        $user = User::findByOid($oid);
        if ($user) {
            $esiauser = EsiaUser::findOne($user->id_esia_user);

            if ($esiauser)
                $esiauser->actualize($esia);

            Yii::$app->user->login($user);
            Yii::$app->user->identity->createAction(Action::ACTION_LOGIN_ESIA);

            //return $this->goHome();
            //return $login;
        }
        else
        {
            $user = new User();
            $user->email = $oid . '@esia.ru';
            $user->username = $personInfo['firstName'] . ' ' . $personInfo['lastName'];
            $user->setPassword($personInfo['eTag']);
            $user->generateAuthKey();
            $user->status = User::STATUS_ACTIVE;

            $esiauser = new EsiaUser();

            if ($esiauser->actualize($esia)) {
                $user->id_esia_user = $esiauser->id_esia_user;
            }
            if (!$user->save()) {
                throw new yii\web\ServerErrorHttpException('Внутренняя ошибка сервера');
            }

            Yii::$app->user->login($user);
            Yii::$app->user->identity->createAction(Action::ACTION_SIGNUP_ESIA);
        }

        if($esiauser->is_org)
        {
            $esiauser->is_org = 0;
            $esiauser->updateAttributes(['is_org']);
        }

        if(isset($roles['elements']) && count($roles['elements']))
        {
            $oids = [];

            foreach($roles['elements'] as $firmInfo)
            {
                $efirm = EsiaFirm::find()->where(['oid' => $firmInfo['oid']])->one();

                $oids[] = $firmInfo['oid'];

                if(!$efirm) {
                    $efirm = new EsiaFirm;
                    $efirm->oid = (string)$firmInfo['oid'];
                }

                $efirm->active = (int)$firmInfo['active'];
                $efirm->fullname = $firmInfo['fullName'];
                $efirm->shortname = $firmInfo['shortName'];
                $efirm->ogrn = $firmInfo['ogrn'];
                $efirm->email = $firmInfo['email'];
                $efirm->id_user = $user->id;
                if(!$efirm->save())
                {
                    //var_dump($efirm->errors);
                    //die();
                }
            }

            $orgsInfo = $esia->getOrgInfo($oids, ['org_shortname', 'org_fullname', 'org_type', 'org_ogrn', 'org_inn', 'org_leg', 'org_kpp', 'org_ctts', 'org_addrs'], $_REQUEST['code'], $_REQUEST['state']);

            foreach($orgsInfo as $foid => $oinf)
            {
                $efirm = EsiaFirm::find()->where(['oid' => $foid])->one();
                if(!$efirm)
                {
                    continue;
                }

                if(isset($oinf['common']))
                {
                    $efirm->inn = $oinf['common']['inn']??null;
                    $efirm->kpp = $oinf['common']['kpp']??null;
                    $efirm->leg = $oinf['common']['leg']??null;
                }

                foreach($oinf['org_addrs']['elements'] as $address)
                {
                    switch ($address['type']){
                        case 'OLG':
                            $efirm->law_addr = ($address['zipCode']??'') . " " .($address['addressStr']??'') . " " . ($address['house']??'');
                            $efirm->law_addr_fias = $address['fiasCode2']??null;
                            $efirm->law_addr_fias_alt = $address['fiasCode2']??null;
                            break;
                        case 'OPS':
                        default:
                            $efirm->main_addr = ($address['zipCode']??'') . " " .($address['addressStr']??'') . " " . ($address['house']??'');
                            $efirm->main_addr_fias = $address['fiasCode2']??null;
                            $efirm->main_addr_fias_alt = $address['fiasCode']??null;
                            break;
                    }
                }

                /*
                if(isset($oinf['org_addrs']['elements'][0]))
                {
                    $efirm->main_addr = ($oinf['org_addrs']['elements'][0]['zipCode']??'') . " " .($oinf['org_addrs']['elements'][0]['addressStr']??'') . " " . ($oinf['org_addrs']['elements'][0]['house']??'');
                    $efirm->main_addr_fias = $oinf['org_addrs']['elements'][0]['fiasCode']??null;
                    $efirm->main_addr_fias_alt = $oinf['org_addrs']['elements'][0]['fiasCode2']??null;
                }
                */

                if(!$efirm->save())
                {
                    //var_dump($efirm->errors);
                    //die();
                }
            }

            $actveFirms = $user->getActiveFirms();

            if($actveFirms)
            {
                return $this->render('firmselect', [
                    'fio' => Yii::$app->user->identity->username,
                    'firms' => $actveFirms,
                    'backUrl' => $backUrl
                ]);
            }
        }

        return $this->redirect($backUrl);
    }

    public function actionAsfirm()
    {
        if(Yii::$app->user->isGuest)
            return $this->redirect('/');

        $ogrn = Yii::$app->request->get('f', 0);

        $efirm = EsiaFirm::find()->where(['id_user' => Yii::$app->user->id, 'oid' => $ogrn])->one();
        $esiauser = EsiaUser::find()->where(['id_esia_user' => Yii::$app->user->identity->id_esia_user])->one();

        if($esiauser && $efirm)
        {
            $esiauser->is_org = $efirm->id_esia_firm;
            $esiauser->updateAttributes(['is_org']);
        }

        $backUrl =  Yii::$app->request->get('r', '/');
        return $this->redirect($backUrl);
    }

    public function actionTestip(){
        echo(Yii::$app->request->userIP);
        echo('<br>');
        echo(Yii::$app->request->remoteIP);
        echo('<br><pre>');
        print_r(Yii::$app->request->headers);
        echo('</pre><br>');
        var_dump($_SERVER);
        echo('<br><pre>');
        print_r(Yii::$app->request->ipHeaders);
        echo('</pre><br>');
        die();
    }

    public function actionMailtest()
    {
        try {
            $res = Yii::$app->mailer->compose('confirm', ['code' => 666 ])
                ->setFrom('ssp@admkrsk.ru')
                ->setTo(['kosyag@gmail.com'])
                ->setSubject("Confirm Your Email Address")
                ->send();

            var_dump($res);
        } catch (\Exception $e) {
            var_dump($e);
         return $this->render('//site/confirmemail_error');
        }

    } 

    public function actionSmevtest()
    {
        $smev1 = new Smev;
        $smev1->testMessage();
    }    

}

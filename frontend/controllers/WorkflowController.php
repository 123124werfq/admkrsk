<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

use Selective\XmlDSig\DigestAlgorithmType;
use Selective\XmlDSig\XmlSigner;
use XmlDsig\XmlDigitalSignature;


use common\models\Pdocument;
use frontend\models\WorkflowForm;

class WorkflowController extends \yii\web\Controller
{

    public function actionIn()
    {
        $model = new WorkflowForm();

        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstances($model, 'file');


//            if ($model->file && $model->validate()) {
                if($model->rawtext)
                {
                    $doc = new Pdocument;
                    $doc->parseAndSave($model->rawtext);
                }
                elseif ($model->file) {
                    foreach ($model->file as $file) {
                        $filename = 'assets/uploads/' . $file->baseName . '.' . $file->extension;

                        if(file_exists($filename))
                            unlink($filename);

                        $file->saveAs('assets/uploads/' . $file->baseName . '.' . $file->extension);

                        $content = file_get_contents($filename);

                        //echo $file->type;
                        //die();

                        switch($file->type){
                            case 'text/xml':
                                $doc = new Pdocument;
                                $doc->parseAndSave($content);
                                break;
                            case 'message/rfc822';
                                $res = imap_fetchbody();
                                break;
                            default:
                                $doc = new Pdocument;
                                $entityBody = file_get_contents('php://input');
                                $doc->parseAndSave($entityBody);
                        }


                    }
                }
                else
                {



                }

        }
    }

    public function actionTestupload()
    {
        $model = new WorkflowForm();
        return $this->render('testupload', ['model' => $model]);
    }

    public function actionXmltest()
    {

        $email = 'johann.******@gmail.com';
        $encrypt = '******************************';
        $notification_id = '**************';
        $random = '********************';
        $senddate = '2013-09-09T00:00:00';
        $synchrotype = 'NOTHING';
        $uidkey = 'EMAIL';


        $params = array(
            'arg0' => array(
                'content' => array( 1 => 'mon_test'),
                'dyn' => array( 'FIRSTNAME' => 'yoyo'),
                'email' => $email,
                'encrypt' => $encrypt,
                'notificationId' => $notification_id,
                'random' => $random,
                'senddate' => $senddate,
                'synchrotype' => $synchrotype,
                'uidkey' => $uidkey
            )
        );


        $client = new       \SoapClient('http://api.notificationmessaging.com/NMSOAP/NotificationService?wsdl', array(  'trace' => 1, 'exceptions' => 0  ) );

        $res = $client->sendObject( $params );

        echo "<pre>";
        echo "REQUEST 1 :" . htmlspecialchars($client->__getLastRequest()) . "<br />";
        echo "RESPONSE 1 :" . htmlspecialchars($client->__getLastResponse()) . "<br /></pre>";

        die();
    }

    public function actionSigntest()
    {
        /*
        $certName = Yii::getAlias('@app'). "/assets/private.pem";

        $dsig = new XmlDigitalSignature();

        $dsig->loadPrivateKey( $certName);
        //$dsig->loadPublicKey('path/to/public/key');
        
        $dsig->addObject('I am a data blob.');
        $dsig->sign();
        
        $result = $dsig->getSignedDocument();

        var_dump($result);

        die();
        */
        
        $certName = Yii::getAlias('@app'). "/assets/ADMKRSK-TEST-SERVICE-SITE.pfx";
        $xmlSigner = new XmlSigner();
        $xmlSigner->loadPfxFile($certName, 'CdtDblGfh');
        //$xmlSigner->setReferenceUri('');
        $xmlSigner->signXmlFile( Yii::getAlias('@app').'/assets/example.xml', Yii::getAlias('@app').'/assets/signed-example.xml', DigestAlgorithmType::SHA1);

        //var_dump($result);
        die();
        

        $certName = Yii::getAlias('@app'). "/assets/ADMKRSK-TEST-SERVICE-SITE.pfx";

        $data = file_get_contents($certName);
        $certPassword = 'CdtDblGfh';
        openssl_pkcs12_read($data, $certs, $certPassword);
        var_dump($certs);
        die();

        $filename = $certName;
        $handle = fopen($filename, "r");
        $contents = fread($handle, filesize($filename));
        fclose($handle);

        //$res = $contents;
        //$res = base64_decode($contents);
        $res = base64_encode($contents);
        echo $res;

        die();

    }


    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function actionInlet()
    {
        $path = Yii::getAlias('@runtime')."/logs/services.log";
        file_put_contents($path,date("r").":\n\n", FILE_APPEND);
        file_put_contents($path,file_get_contents('php://input'), FILE_APPEND);
        file_put_contents($path,"\n", FILE_APPEND);
    }    

    public function actionAppealsinput()
    {
        $path = Yii::getAlias('@runtime')."/logs/appeals.log";
        file_put_contents($path,date("r").":\n\n", FILE_APPEND);
        file_put_contents($path,file_get_contents('php://input'), FILE_APPEND);
        file_put_contents($path,"\n", FILE_APPEND);
    }  
    
    public function actionDocsinput()
    {
        $path = Yii::getAlias('@runtime')."/logs/docs.log";
        file_put_contents($path,date("r").":\n\n", FILE_APPEND);
        file_put_contents($path,file_get_contents('php://input'), FILE_APPEND);
        file_put_contents($path,"\n", FILE_APPEND);
    }      
}

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

    protected function signXML($sourcePath, $resultPath, $attachment = null)
    {
        $certName = Yii::getAlias('@app'). "/assets/ADMKRSK-TEST-SERVICE-SITE.pfx";

        if($attachment && file_exists($attachment)){
            //$toReplace = '<rev:Reference><xop:Include href="cid:5aeaa450-17f0-4484-b845-a8480c363444" xmlns:xop="http://www.w3.org/2004/08/xop/include" /></rev:Reference>';
            $toReplace = '<xop:Include href="cid:5aeaa450-17f0-4484-b845-a8480c363444" xmlns:xop="http://www.w3.org/2004/08/xop/include" />';
            $fp = fopen($attachment, "rb");
            $binary = fread($fp, filesize($attachment));
            $attachment64 = base64_encode($binary);
            $attachment64 = chunk_split($attachment64, 76, "\r\n"); 
            //$digest = base64_encode(pack('H*', hash('sha1',$attachment64)));  // считаем дайджест архива: хэш sha1 -> ASCII -> base64
            $digest = base64_encode(sha1($binary));


            echo ("Подписываем файл: $sourcePath<br>");

            $sourceText = file_get_contents($sourcePath); 
            $sourceText = str_replace($toReplace, $attachment64, $sourceText); // заменям ссылку файлоы (возможно, не надо)
            $sourceText = str_replace('ATTDIGESTHERE', $digest, $sourceText); // записываем дайджест ФАЙЛа (дайдже xml запишется при подписи)

            $tempPath = str_replace('.xml', '_temp.xml', $sourcePath); // формирум файл, который будем подписывать
            file_put_contents($tempPath,$sourceText);
            $sourcePath = $tempPath;
        }

        $xmlSigner = new XmlSigner();
        $xmlSigner->loadPfxFile($certName, 'CdtDblGfh');
        $xmlSigner->signXmlFile( $sourcePath, $resultPath, DigestAlgorithmType::SHA1); // подписали

        echo ("Подписываем файл: $sourcePath<br>");
        echo ("Результирующий файл: $resultPath<br>");

        if($attachment && file_exists($attachment)){
            $resultXML = file_get_contents($resultPath);
            $resultXML = str_replace($attachment64, $toReplace, $resultXML);
            file_put_contents($resultPath,$resultXML); // заменили файл на ссылку назад (возмжно, лишнее)
        }
    }


    public function actionXopcreate()
    {
        $source = Yii::getAlias('@app').'/assets/template_attachment.xml';
//        $source = Yii::getAlias('@app').'/assets/example.xml';
        $xmlPath = Yii::getAlias('@app').'/assets/signed'.time().'.xml';
        $output = Yii::getAlias('@app').'/assets/tosend'.time().'.txt';
        $attachment = Yii::getAlias('@app').'/assets/archive.zip';

        $this->signXML($source, $xmlPath, $attachment);
//        $this->signXML($source, $xmlPath);

        if(!file_exists($xmlPath))
            return false;

        $mtomHeader = <<<MTOMHEAD
MIME-Version: 1.0
Content-Type: multipart/related; start="<rootpart*c73c9ce8-6e02-40ce-9f68-064e18843428>"; start-info="text/xml"; type="application/xop+xml"; boundary="MIME_boundary";


--MIME_boundary
Content-Type: application/xop+xml;charset=utf-8;type="text/xml"
Content-Id: <rootpart*c73c9ce8-6e02-40ce-9f68-064e18843428>
Content-Transfer-Encoding: binary


MTOMHEAD;

        $mtomArchivehead = <<<MTMOARCH

--MIME_boundary
Content-Type: application/zip
Content-Id: 5aeaa450-17f0-4484-b845-a8480c363444
Content-Transfer-Encoding: base64

MTMOARCH;

        $mtomClose = "--MIME_boundary--";

        if(file_exists($attachment))
        {
            $fp = fopen($attachment, "rb");
            $binary = fread($fp, filesize($attachment));
            $attachment64 = base64_encode($binary);  
            $attachment64 = chunk_split($attachment64, 76, "\r\n");          
        }

        $result = $mtomHeader;
        $result .= file_get_contents($xmlPath);
        $result .= $mtomArchivehead;
        $result .= "\r\n" . $attachment64 . "\r\n";
        $result .= "\r\n" . $mtomClose;

        file_put_contents($output, $result);
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

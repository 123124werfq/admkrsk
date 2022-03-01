<?php
// Класс для работы с СЭД


namespace common\models;

use Yii;
use yii\base\Model;

use Selective\XmlDSig\DigestAlgorithmType;
use Selective\XmlDSig\XmlSigner;
use XmlDsig\XmlDigitalSignature;

class Workflow extends Model
{

    private $sendServiceURL = 'http://10.24.0.201/WSSiteRSA';
    private $sendAppealURL = 'http://10.24.0.201/WSVRRSA';

    private $path = '/tmp/';

    private $debug = false;

    public $error;

    public $guid;

    static protected function xmlToArray($xml)
    {
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
        $xml = new SimpleXMLElement($response);
        $body = $xml->xpath('//SBody')[0];
        $res = json_decode(json_encode((array)$body), TRUE);
        return $res;
    }

    private function generateGuid()
    {
      if (function_exists('com_create_guid') === true)
      {
          return trim(com_create_guid(), '{}');
      }
      return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));      
    }


    private function sendPost($message, $url, $archive = false)
    {
        $this->error = '';

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $message);
            if(curl_exec($curl) === false)
            {
                $this->error = curl_error($url);
                curl_close($curl);
                return false;
            }
            else
            {
                curl_close($curl);
                return true;
            }
        }
    }

    public function sendTest()
    {
        $url = $this->sendServiceURL;
        $message = $this->serviceTestTemplate2;
        $this->error = '';

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $message);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = [
                'SOAPAction:urn:#Operation_03_00_004FL',
                'Cache-Control: no-cache',
                'Content-Type: application/xml'
            ];

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $server_output = curl_exec($curl);

            print_r($server_output);
            //die();

            if(curl_exec($curl) === false)
            {
                $this->error = curl_error($url);
                curl_close($curl);
                return false;
            }
            else
            {
                curl_close($curl);
                return true;
            }
        }
    }

    public function sendTest1()
    {

        $resp = file_get_contents($this->sendServiceURL);
        var_dump($resp);
        die();

        $url = $this->sendServiceURL;
        $message = $this->serviceTestTemplate2;
        echo $message;
        echo "<br>-------<br>";
        $this->error = '';

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $message);
            $headers = [
                'SOAPAction:urn:#Operation_03_00_004FL',
                //'SOAPAction:Operation_03_00_004FL',
                'Content-Type: text/xml; charset=utf-8',
                'Content-Length: '.strlen($message),
                'Content-Transfer-Encoding: text'
            ];

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $server_output = curl_exec($curl);

            print_r($server_output);
            //die();

            if(curl_exec($curl) === false)
            {
                $this->error = curl_error($url);
                curl_close($curl);
                return false;
            }
            else
            {
                curl_close($curl);
                return true;
            }
        }
    }


    public function sendTest2()
    {
        $url = $this->sendAppealURL;
        $message = $this->appealTestTemplate3;
        $this->error = '';

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $message);

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = [
                //'SOAPAction:urn:#Operation_03_00_004FL',
            ];

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $server_output = curl_exec($curl);

            print_r($server_output);
            //die();

            if(curl_exec($curl) === false)
            {
                $this->error = curl_error($url);
                curl_close($curl);
                return false;
            }
            else
            {
                curl_close($curl);
                return true;
            }
        }
    }

    public function sendTestraw()
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://10.24.0.201/WSSiteRSA",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "<?xml version='1.0' encoding='UTF-8'?>\n<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:ds=\"http://www.w3.org/2000/09/xmldsig#\" xmlns:wsse=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd\" xmlns:wsu=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:int=\"http://intertrust.ru/\" xmlns:rev=\"http://smev.gosuslugi.ru/rev120315\" xmlns:admkrsk=\"http://smev.admkrsk.ru/v1.0\">\n  <soapenv:Header><wsse:Security soapenv:actor=\"http://smev.gosuslugi.ru/actors/smev\"><wsse:BinarySecurityToken EncodingType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary\" ValueType=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3\" wsu:Id=\"CertId-6363A43D57E642FBB5B1AA8A89206A4F\">MIIEJzCCAxOgAwIBAgIQLRPgc19QHIJIfqrdlOJ/rjAJBgUrDgMCHQUAMIGXMQswCQYDVQQGEwJSVTEbMBkGA1UECBMSS3Jhc25veWFyc2tpeSBrcmF5MRQwEgYDVQQHEwtLcmFzbm95YXJzazEoMCYGA1UEChMfS3Jhc25veWFyc2sgQ2l0eSBBZG1pbmlzdHJhdGlvbjEMMAoGA1UECxMDVUlTMR0wGwYDVQQDExRBRE1LUlNLLVRFU1QtUk9PVC1DQTAgFw0xMjEyMzExNjAwMDBaGA8yMDk5MTIzMTE2MDAwMFowgZwxCzAJBgNVBAYTAlJVMRswGQYDVQQIExJLcmFzbm95YXJza2l5IGtyYXkxFDASBgNVBAcTC0tyYXNub3lhcnNrMSgwJgYDVQQKEx9LcmFzbm95YXJzayBDaXR5IEFkbWluaXN0cmF0aW9uMQwwCgYDVQQLEwNVSVMxIjAgBgNVBAMTGUFETUtSU0stVEVTVC1TRVJWSUNFLVNJVEUwgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAN1NinggsY6Q6EcaWJLerxu9a4IyaEDejwcDWxuhYkBVYVsbFDtNu5cYWIZH0gLmm3KlnVYwV2jSTQ6o0r1zTKQcwvqd1PboXFUJzFY9jrnEUGNHsUmZH7vFM4jRGZFAVlapmBCOqSI29PiosAzYbasf8XR5Wn9cRJ5vooo7QzS5AgMBAAGjgfEwge4wHQYDVR0lBBYwFAYIKwYBBQUHAwEGCCsGAQUFBwMCMIHMBgNVHQEEgcQwgcGAEHU9JD0e1ZppnIyZpG+NLMKhgZowgZcxCzAJBgNVBAYTAlJVMRswGQYDVQQIExJLcmFzbm95YXJza2l5IGtyYXkxFDASBgNVBAcTC0tyYXNub3lhcnNrMSgwJgYDVQQKEx9LcmFzbm95YXJzayBDaXR5IEFkbWluaXN0cmF0aW9uMQwwCgYDVQQLEwNVSVMxHTAbBgNVBAMTFEFETUtSU0stVEVTVC1ST09ULUNBghCSz/d+rLh4lUjnZPCGJYOmMAkGBSsOAwIdBQADggEBABkdZ9naW0M+AW9XAWTLTApUrju/gRevzAIY8XbwSkmIAO0ljXQeR0RFeYF5AI0+70w/lFYPyTNFmJh5GAgdNUIPsWbKI4WE8dZKUY91jkj/U9fX4vLMFK6rKrF9ZXwyS3Nxs0QTgloPZSPibd/OlYuzrZc6v/RMxS8ezRpZ9qS2GtB+I4w7CudGDD32uaWWMnZQRwH2wtciRk+p3vCylyvGtEoKRc4HWMjCtf0/BcglxXEWyy7K/frzq0YJ8w5qgv7lSrFE/2jYQCrVONPqTNEcFkap/ToZMv5jRxXoJN7G7iRqWPu29M4XBnBBv7MEDNHa/gOceroYoVOZ0rSZ444=</wsse:BinarySecurityToken><ds:Signature><SignedInfo xmlns=\"http://www.w3.org/2000/09/xmldsig#\"><CanonicalizationMethod Algorithm=\"http://www.w3.org/2001/10/xml-exc-c14n#\" /><SignatureMethod Algorithm=\"http://www.w3.org/2000/09/xmldsig#rsa-sha1\" /><Reference URI=\"#BodyId-F01F22D69F264C8CB33F8E5C95DDE883\"><Transforms><Transform Algorithm=\"http://www.w3.org/2001/10/xml-exc-c14n#\" /></Transforms><DigestMethod Algorithm=\"http://www.w3.org/2000/09/xmldsig#sha1\" /><DigestValue>w4or6cBBT8kxa+DG8tvvd8nrOL8=</DigestValue></Reference></SignedInfo><SignatureValue xmlns=\"http://www.w3.org/2000/09/xmldsig#\">t0zGVInuwkwIqpN7sh1Nin3jJJZLnqcDV7RWqs25GQ/81jc8LeWsmvdA+Q6P0xmK1261YAPZwpKf4lQ6R4xSgsNSNsMqCKUpQwYlkuT5iS62dsyrr+TrTgVb6O0QpeiJWQop7Peb6M3FO7c8UG48bEeENTPgDjp4OwPm8kG40q4=</SignatureValue><ds:KeyInfo><wsse:SecurityTokenReference><wsse:Reference URI=\"#CertId-6363A43D57E642FBB5B1AA8A89206A4F\" /></wsse:SecurityTokenReference></ds:KeyInfo></ds:Signature></wsse:Security></soapenv:Header>\n  <soapenv:Body wsu:Id=\"BodyId-F01F22D69F264C8CB33F8E5C95DDE883\" xmlns:wsu=\"http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd\">\n    <int:Input_03_00_004FL>\n      <rev:Message>\n        <rev:Sender>\n          <rev:Code>OSAK01241</rev:Code>\n          <rev:Name>Официальный сайт администрации города Красноярска</rev:Name>\n        </rev:Sender>\n        <rev:Recipient>\n          <rev:Code>236402241</rev:Code>\n          <rev:Name>Электронный документооборот администрации города Красноярска</rev:Name>\n        </rev:Recipient>\n        <rev:Originator>\n          <rev:Code>OSAK01241</rev:Code>\n          <rev:Name>Официальный сайт администрации города Красноярска</rev:Name>\n        </rev:Originator>\n        <rev:ServiceName>03/00/004</rev:ServiceName>\n        <rev:TypeCode>GSRV</rev:TypeCode>\n        <rev:Status>REQUEST</rev:Status>\n        <rev:Date>2019-08-20T18:32:39.4965852+07:00</rev:Date>\n        <rev:ExchangeType>1</rev:ExchangeType>\n        <rev:ServiceCode>2400000010000000000</rev:ServiceCode>\n        <rev:CaseNumber>7A06C1C502184672A6EB7EF46529803E</rev:CaseNumber>\n      </rev:Message>\n      <rev:MessageData>\n        <rev:AppData>\n          <OrderID>6995</OrderID>\n          <ServiceCode>03/00/004</ServiceCode>\n          <ServiceTarget>выписка на жилое помещение</ServiceTarget>\n          <ServicePlace>Город</ServicePlace>\n          <DocDate>2019-08-20</DocDate>\n          <FL_FIO LastName=\"Потапов\" FirstName=\"Александр\" MiddleName=\"Владимирович\" />\n          <FL_SNILS>148-049-977 00</FL_SNILS>\n          <FL_PASSPORT PassportSeriesAndNum=\"0408 №650182\" PassportWhoIssued=\"Территориальным пунктом УФМС России по Красноярскому краю в Тасеевском районе\" PassportWhenIssued=\"2008-11-05\" />\n          <ADR ADR_Country=\"Россия\" ADR_Region_Code=\"2400000000000\" ADR_Region=\"Красноярский край\" ADR_City_Code=\"2400000100000\" ADR_City=\"Красноярск г\" ADR_City_District=\"Советский район\" ADR_Street_Code=\"240000010000322\" ADR_Street=\"Краснодарская ул\" ADR_House=\"14\" ADR_Zip=\"660005\" />\n          <FL_ContactInfo Tel=\"7 (904) 897-45-56\" />\n          <FL_SUBJECT>\tПрошу предоставить выписку из Реестра муниципальной собственности на\n\t квартира\n\tрасположенное по адресу:\n\tулица, г. Красноярск Краснодарская\n\tномер дома (строения/литера - при наличии) 14\n\tномер жилого помещения в доме 11\n\nСпособ получения выписки:\nЛично -\nПочтовым отправлением по адресу -\nНаправить в электронном виде - Да\nВыдать через МФЦ (в случае, если заявление было подано в МФЦ) -\n\nДата и время запроса услуги на Сайте: 20.08.2019 18:32.\nРегистрационный номер запроса услуги на Сайте: 03/00/004-0438.\nЛогин пользователя на Сайте: esia#1056781441@gosuslugi.ru.\nУровень достоверности: Подтвержденная учетная запись ЕСИА физического лица, аутентификация по логину и паролю.</FL_SUBJECT>\n          <FIELDS>\n            <Field FLD_Name=\"Без имени\" FLD_Type=\"string\" FLD_Value=\"квартира\" />\n            <Field FLD_Name=\"Без имени\" FLD_Type=\"string\" FLD_Value=\"Краснодарская\" />\n            <Field FLD_Name=\"Без имени\" FLD_Type=\"string\" FLD_Value=\"14\" />\n            <Field FLD_Name=\"Без имени\" FLD_Type=\"string\" FLD_Value=\"11\" />\n            <Field FLD_Name=\"Без имени\" FLD_Type=\"string\" FLD_Value=\"здание\" />\n            <Field FLD_Name=\"Без имени\" FLD_Type=\"string\" FLD_Value=\"прилагается\" />\n            <Field FLD_Name=\"Способ получения выписки\" FLD_Code=\"Лично\" FLD_Type=\"string\" FLD_Value=\"\">false</Field>\n            <Field FLD_Name=\"Способ получения выписки\" FLD_Code=\"Почтовым отправлением по адресу\" FLD_Type=\"string\" FLD_Value=\"\">false</Field>\n            <Field FLD_Name=\"Способ получения выписки\" FLD_Code=\"Направить в электронном виде\" FLD_Type=\"string\" FLD_Value=\"Да\">true</Field>\n            <Field FLD_Name=\"Способ получения выписки\" FLD_Code=\"Выдать через МФЦ (в случае, если заявление было подано в МФЦ)\" FLD_Type=\"string\" FLD_Value=\"\">false</Field>\n          </FIELDS>\n          <AuthorProfile ConfidenceLevel=\"Подтвержденная учетная запись ЕСИА физического лица, аутентификация по логину и паролю\" Login=\"esia#1056781441@gosuslugi.ru\" RequestRegNum=\"03/00/004-0438\" />\n        </rev:AppData>\n        <rev:AppDocument>\n          <rev:RequestCode>req_7a06c1c5-0218-4672-a6eb-7ef46529803e</rev:RequestCode>\n          <rev:Reference>\n            <xop:Include href=\"cid:5aeaa450-17f0-4484-b845-a8480c363444\" xmlns:xop=\"http://www.w3.org/2004/08/xop/include\" />\n          </rev:Reference>\n          <rev:DigestValue>DNmdDWA7PdxrjvHOVmmUUOpUf/M=</rev:DigestValue>\n        </rev:AppDocument>\n      </rev:MessageData>\n    </int:Input_03_00_004FL>\n  </soapenv:Body>\n</soapenv:Envelope>",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: text/xml",
                "SOAPAction: urn:#Operation_03_00_004FL",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        var_dump($response);
        var_dump($err);
        echo "<hr>";
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }


    protected function rawTransport($url)
    {
        $fp = fsockopen($url, 80, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "POST / HTTP/1.1\r\n";
            $out .= "Host: www.example.com\r\n";
            $out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            while (!feof($fp)) {
                echo fgets($fp, 128);
            }
            fclose($fp);
        }
    }


    public function sendMultipartTest()
    {
        $url = $this->sendServiceURL;

        $body = '';

        /*
        $soapBoundaryId = "c73c9ce8-6e02-40ce-9f68-064e18843428";
        $zipBoundaryId = "5aeaa450-17f0-4484-b845-a8480c363444";
        $boundary = "MIME_boundary";
        $boundarybytes = "\r\n--{$boundary}\r\n";
        $boundarybytesEnd = "\r\n--{$boundary}--";
        $rn = "\r\n";

        $headerbytes  = "MIME-Version: 1.0\r\n";
        $headerbytes .= "Content-Type: multipart/related; ";
        $headerbytes .= "start=\"<rootpart*{$soapBoundaryId}>\"; ";
        $headerbytes .= "start-info=\"text/xml\"; ";
        $headerbytes .= "type=\"application/xop+xml\"; ";
        $headerbytes .= "boundary=\"MIME_boundary\";\r\n\r\n";

        $soapheaderbuf = "Content-Type: application/xop+xml;charset=utf-8;type=\"text/xml\"\r\n";
        $soapheaderbuf .= "Content-Id: <rootpart*{$soapBoundaryId}>\r\n";
        $soapheaderbuf .= "Content-Transfer-Encoding: binary\r\n";

        $rawMessage = $this->serviceTestTemplate2;

        $body .= $headerbytes;
        $body .= $boundarybytes;
        $body .= $soapheaderbuf;
        $body .= $rn;
        $body .= $rawMessage;

        // тут файл подключаем, если есть

        $body .= $boundarybytesEnd;
           */

        $body = file_get_contents(Yii::getAlias('@app').'/assets/testrequest.txt');
/*
        $fp = fsockopen('10.24.0.201', 80, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            $out = "POST /WSSiteRSA HTTP/1.1\r\n";
            $out .= "Host: t1.admkrsk.ru1\r\n";
            $out .= "Content-Length: ".strlen($body)."\r\n";
            $out .= "Content-Type: multipart/related;";
            //$out .= "Content-Type: application/x-www-form-urlencoded\r\n\r\n";
            $out .= $body;
            //$out .= "Connection: Close\r\n\r\n";

            //echo "<h1>Request</h1><pre>".htmlspecialchars($out)."</pre>";

            //$out .= "Connection: Close\r\n\r\n";
            fwrite($fp, $out);
            echo "<h1>Response</h1><pre>";
            while (!feof($fp)) {
                echo htmlspecialchars(fgets($fp, 128));
            }
            echo "</pre>";
            fclose($fp);
        }

        die();
*/
        $headers = [
            //'SOAPAction:urn:#Operation_03_00_004FL',
            'Content-Type: multipart/related; charset=utf-8',
            'Content-Length: '.strlen($body),
            'Content-Transfer-Encoding: text'
        ];

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $server_output = curl_exec($curl);

            print_r($server_output);
            die();
        }
    }


    public function sendAppealMessage($appealRecord)
    {
        return $this->sendPost($appealRecord->toString(), $this->sendAppealURL);
    }

    public function getAppealMessage($raw)
    {

    }

    public function sendServiceMessage(ServiceAppeal $serviceRecord)
    {
        $record = CollectionRecord::findOne($serviceRecord->id_record);

        $opres = $this->generateServiceRequest($record);

        if($this->debug)
            return true;

        return $this->sendPost($opres, $this->sendServiceURL);
    }


    public function sendServiceMultipartMessage($fliePathToSend)
    {
      $url = $this->sendServiceURL;

      if(!file_exists($fliePathToSend))
        return false;

      $body = file_get_contents($fliePathToSend);

      $headers = [
        //'SOAPAction:urn:#Operation_03_00_004FL',
        'Content-Type: multipart/related; charset=utf-8',
        'Content-Length: '.strlen($body),
        'Content-Transfer-Encoding: text'
      ];

      if( $curl = curl_init() ) {
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
          curl_setopt($curl, CURLOPT_POST, true);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

          $server_output = curl_exec($curl);

        return $server_output;
      }      
    }

    public function getServiceMessage($raw)
    {

    }

    public function makeSign($filename)
    {
      
      if(!file_exists($filename))
        return false;

      $pemPath = escapeshellcmd('/var/www/admkrsk/common/config/ADMKRSKTESTSERVICESITE_cert_out.pem');
      $keyPath = escapeshellcmd('/var/www/admkrsk/common/config/ADMKRSKTESTSERVICESITE_cert_out.pem');

      $filePath = escapeshellcmd($filename);
      $path_parts = pathinfo($filename);
      $resultPath = escapeshellcmd($path_parts['dirname'].'/'.$path_parts['basename'].'.sig');
      
      /*
      //openssl_pkcs7_sign($filePath, $resultPath, $pemPath, array($pemPath,"CdtDblGfh"),[], PKCS7_BINARY | PKCS7_DETACHED);
      $res = openssl_pkcs7_sign($filePath, $resultPath."-mime", 'file://'.realpath($pemPath), ['file://'.realpath($keyPath), "CdtDblGfh"],[], PKCS7_BINARY | PKCS7_DETACHED);
      var_dump($res);

      if($res)
      {
        $signedMime = file_get_contents($resultPath."-mime");
        $sparts = explode('Content-Disposition: attachment; filename="smime.p7s"',$signedMime);

        //var_dump($sparts[1][0]);

        $cparts = explode('------', $sparts[1]);

        //var_dump($cparts[0]);

        $b64 = str_replace($sparts[1][0], '', $cparts[0]);

        //var_dump($b64);

        $fp = fopen($resultPath, "wb");
        fwrite($fp,  base64_decode($b64));

      }
      */
      $command = "openssl cms -sign -signer $pemPath -inkey $keyPath -binary -in $filePath  -nosmimecap -md sha1 -outform der -out $resultPath";
      exec($command, $output, $return_var);

      $result =  file_exists($resultPath);

      return $result?$resultPath:false;
    }

    public function generateArchive($guid, $attachments = [], $formFile = false)
    {
        $zip_path = Yii::getAlias('@runtime') . $this->path . 'req_' . $guid. '.zip';


        $zip = new \ZipArchive();

        if (is_file($zip_path))
            unlink($zip_path);

        $filesToUnlink = [];

        $xmlParts = [];

        if ($zip->open($zip_path,\ZIPARCHIVE::CREATE) === TRUE)
        {
            foreach ($attachments as $key => $att)
            {

                $arrContextOptions=array(
                  "ssl"=>array(
                      "verify_peer"=>false,
                      "verify_peer_name"=>false,
                  ),
                );  
                $tfile = file_get_contents($att, false, stream_context_create($arrContextOptions));
                $ext = explode(".", $att);

                $ext = end($ext);
                if($ext == 'zip')
                    $ext = 'gz';

                $tpath = Yii::getAlias('@runtime') . $this->path . "req_" . $guid . "." . $ext;

                file_put_contents($tpath, $tfile);

                if (is_file($tpath)) {
                    $zip->addFile($tpath, 'req_' . $guid . "." . $ext);

                    if($signFname = $this->makeSign($tpath))
                    {
                      $path_parts = pathinfo($signFname);                
                      $zip->addFile($signFname, $path_parts['basename']);
                    }

                    $filesToUnlink[] = $tpath;

                    $dg = $this->generateDigestForFile($tpath);
                    $fn = "req_" . $guid . "." . $ext;
                    $afn = "Приложение_" . "_". $key . "_" . $guid . "." . $ext;

                    $xmlParts[] = <<<XMLPARTS1
<rev:AppliedDocument>
  <rev:Name>$fn</rev:Name>
  <rev:URL>/$fn</rev:URL>
  <rev:DigestValue>$dg</rev:DigestValue>
  <Description>ПРИЛОЖЕНИЕ $key</Description>
</rev:AppliedDocument>                    
XMLPARTS1;
                }
            }

            if(file_exists($formFile))
            {
                $tfile = file_get_contents($formFile);
                $docPath = Yii::getAlias('@runtime') . $this->path . "req_" . $guid . ".docx";

                file_put_contents($docPath, $tfile);

                if (is_file($docPath)) {
                    $zip->addFile($docPath, 'req_' . $guid . ".docx");

                    if($signFname = $this->makeSign($docPath))
                    {
                      $path_parts = pathinfo($signFname);                
                      $zip->addFile($signFname, $path_parts['basename']);
                      $filesToUnlink[] = $signFname;
                    }

                    $filesToUnlink[] = $docPath;

                    $dg = $this->generateDigestForFile($docPath);
                    $fn = "req_" . $guid . ".docx";
                    $afn = "Форма_" . $guid . ".docx";

                    $xmlParts[] = <<<XMLPARTS2
      <rev:AppliedDocument>
        <rev:Name>$afn</rev:Name>
        <rev:URL>/$fn</rev:URL>
        <rev:DigestValue>$dg</rev:DigestValue>
        <Description>ЗАЯВЛЕНИЕ</Description>
      </rev:AppliedDocument>                    
XMLPARTS2; 
                }
            }

            $masterAuthPath = '/var/www/admkrsk/common/config/master.auth';
            if(file_exists($masterAuthPath))
            {
                $tfile = file_get_contents($masterAuthPath);
                $docPath = Yii::getAlias('@runtime') . $this->path . "req_" . $guid . ".auth";

                file_put_contents($docPath, $tfile);

                if (is_file($docPath)) 
                {
                    $zip->addFile($docPath, 'req_' . $guid . ".auth");

                    if($signFname = $this->makeSign($docPath))
                    {
                      $path_parts = pathinfo($signFname);
                      $zip->addFile($signFname,$path_parts['basename']);
                      $filesToUnlink[] = $signFname;
                    }

                    $filesToUnlink[] = $docPath;

                    $dg = $this->generateDigestForFile($docPath);
                    $fn = "req_" . $guid . ".auth";

                    $xmlParts[] = <<<XMLPARTS2
      <rev:AppliedDocument>
        <rev:Name>Удостоверение пользователя (ЕСИА).auth</rev:Name>
        <rev:URL>/$fn</rev:URL>
        <rev:DigestValue>$dg</rev:DigestValue>
      </rev:AppliedDocument>                    
XMLPARTS2;                      
                }                
            }

            // теперь надо составить список всех файлов и его тоже подписать
            $xmlPath = Yii::getAlias('@runtime') . $this->path . "req_" . $guid . ".xml";
            $xmlContents = "<rev:AppliedDocuments xmlns:rev=\"http://smev.gosuslugi.ru/rev120315\">\n".implode("\n", $xmlParts)."\n</rev:AppliedDocuments>";
            file_put_contents($xmlPath, $xmlContents);

            $zip->addFile($xmlPath, 'req_' . $guid . ".xml");

            if($signFname = $this->makeSign($xmlPath))
            {
              $path_parts = pathinfo($signFname);                
              $zip->addFile($signFname, $path_parts['basename']);
              $filesToUnlink[] = $signFname;
            }   

            $zip->close();

            //$filesToUnlink = []; //временно
            foreach ($filesToUnlink as $ufile)
                if(is_file($ufile)) unlink($ufile);

            return $zip_path;
        }
    }

    private function generateDigestForFile($filePath)
    {
      if(!file_exists($filePath))
        return false;
      $fp = fopen($filePath, "rb");
      $binary = fread($fp, filesize($filePath));
      $attachment64 = base64_encode($binary);
      $attachment64 = chunk_split($attachment64, 76, "\r\n"); 
      $digest = base64_encode(pack('H*', hash('sha1',$binary)));       

      return $digest;
    }

    public function generateServiceRequest(CollectionRecord $record)
    {
        $recordItems = $record->getData(true);
        $targetItems = [];
        if(isset($recordItems['id_target']))
            $targetItems = ServiceTarget::findOne($recordItems['id_target']);

        $fixedFields = [
            'name',
            'surname',
            'parental_name'
        ];

        $formFields = [];

        foreach ($recordItems as $rkey => $ritem)
        {
            if(!in_array($rkey, $fixedFields)) {
                $column = CollectionColumn::find()->where(['id_collection' => $record->id_collection, 'alias' => $rkey])->one();
                $formFields[$rkey] = ['value' => $ritem, 'name' => $column->name];
            }
        }

        //var_dump($formFields);
        //var_dump($recordItems);
        //var_dump($targetItems);

        //die();

        $processed = $this->serviceRequestTemplate;
        $processed = str_replace("{fields_list}", "", $processed);

        return $processed;
    }


    // метод подписи XML, протестирован руками
    protected function signServiceXML($sourcePath, $resultPath, $attachment = null, $appeal = null, $insdata = [])
    {
        $certName = "/var/www/admkrsk/common/config/ADMKRSK-TEST-SERVICE-SITE.pfx";

        if($attachment && file_exists($attachment)){
            $toReplace = '<xop:Include href="cid:5aeaa450-17f0-4484-b845-a8480c363444" xmlns:xop="http://www.w3.org/2004/08/xop/include" />';
            $fp = fopen($attachment, "rb");
            $binary = fread($fp, filesize($attachment));
            $attachment64 = base64_encode($binary);
            $attachment64 = chunk_split($attachment64, 76, "\r\n"); 
            $digest = base64_encode(pack('H*', hash('sha1',$binary)));  // считаем дайджест архива: хэш sha1 -> ASCII -> base64
            //$digest = base64_encode(sha1($binary));
            //echo ("Подписываем файл: $sourcePath<br>");
            //echo ("Дайджест: $digest<br>");

            $sourceText = file_get_contents($sourcePath); 
            //$sourceText = str_replace($toReplace, $attachment64, $sourceText); // заменям ссылку файлоы (возможно, не надо)
            $sourceText = str_replace('ATTDIGESTHERE', $digest, $sourceText); // записываем дайджест ФАЙЛа (дайдже xml запишется при подписи)

            $path_parts = pathinfo($attachment);                
            $idreq = str_replace('.zip', '', $path_parts['basename']);
            $sourceText = str_replace('ATREQCODEHERE', $idreq, $sourceText);

            // ставим метку времени вместо Order ID; решение временное, но пусть будет так
            $ordid = time();
            $sourceText = str_replace('ORDERID', $ordid, $sourceText);

            //  заменяем в шаблоне фактические данные
            if($appeal)
            {
              $sourceText = str_replace('REQDATEHERE', date("d-m-Y"), $sourceText);
              $sourceText = str_replace('CASENUMBERHERE', $appeal->number_internal, $sourceText);
              $sourceText = str_replace('SERVICECODEHERE', $appeal->target->service_code, $sourceText);
              $sourceText = str_replace('REESTRNUMBERHERE', $appeal->target->reestr_number, $sourceText);

              $sourceText = str_replace('SERVICETARGETHERE', $appeal->target->form->fullname, $sourceText);
              $sourceText = str_replace('SUBJECTHERE', $appeal->service->subject, $sourceText);

              $tagparts = explode("/", $appeal->target->reestr_number);
              if(count($tagparts)>=3)
              {
                $tagstring = "Input_{$tagparts[0]}_{$tagparts[1]}_{$tagparts[2]}FL";
                $sourceText = str_replace('TAGSERVICEHERE', $tagstring, $sourceText);
              }
              if(!empty($insdata))
              {
                //$userData = json_decode($appeal->data, true);
                $userData = $insdata;
                if(isset($userData['firstname'])) $sourceText = str_replace('firstname', $userData['firstname'], $sourceText);
                if(isset($userData['secondname'])) $sourceText = str_replace('secondname', $userData['secondname'], $sourceText);
                if(isset($userData['middlename'])) $sourceText = str_replace('middlename', $userData['middlename'], $sourceText);
                if(isset($userData['passport_Seria'])) $sourceText = str_replace('{passport_serie}', $userData['passport_Seria'], $sourceText);
                if(isset($userData['passport_number'])) $sourceText = str_replace('{passport_number}', $userData['passport_number'], $sourceText);
                if(isset($userData['passport_out'])) $sourceText = str_replace('{passport_issued}', $userData['passport_out'], $sourceText);
                if(isset($userData['passport_outDate'])) $sourceText = str_replace('{passport_date}', $userData['passport_outDate'], $sourceText);
                if(isset($userData['home_address']['country'])) $sourceText = str_replace('{addr_country}', $userData['home_address']['country'], $sourceText);
                if(isset($userData['home_address']['region'])) $sourceText = str_replace('{addr_region}', $userData['home_address']['region'], $sourceText);
                if(isset($userData['home_address']['city'])) $sourceText = str_replace('{addr_city}', $userData['home_address']['city'], $sourceText);
                if(isset($userData['home_address']['district'])) $sourceText = str_replace('{addr_district}', $userData['home_address']['district'], $sourceText);
                if(isset($userData['home_address']['street'])) $sourceText = str_replace('{addr_street}', $userData['home_address']['street'], $sourceText);
                if(isset($userData['home_address']['house'])) $sourceText = str_replace('{addr_house}', $userData['home_address']['house'], $sourceText);
                if(isset($userData['home_address']['postalcode'])) $sourceText = str_replace('{addr_zip}', $userData['home_address']['postalcode'], $sourceText);
                if(isset($userData['fl_phone'])) $sourceText = str_replace('{phone}', $userData['fl_phone'], $sourceText);
              }
            }
          
            // удаляем то, чего не нашлось
            $sourceText = str_replace(['{phone}','firstname','secondname','middlename','{passport_serie}','{passport_number}','{passport_issued}','{passport_date}','{addr_country}','{addr_region}','{addr_city}','{addr_district}','{addr_street}','{addr_house}','{addr_zip}'], '', $sourceText);

            $tempPath = str_replace('.xml', '_temp.xml', $sourcePath); // формирум файл, который будем подписывать
            file_put_contents($tempPath,$sourceText);
            $sourcePath = $tempPath;
        }

        $xmlSigner = new XmlSigner();
        $xmlSigner->loadPfxFile($certName, 'CdtDblGfh');
        $xmlSigner->signXmlFile( $sourcePath, $resultPath, DigestAlgorithmType::SHA1); // подписали

        //echo ("Подписываем файл: $sourcePath<br>");
        //echo ("Результирующий файл: $resultPath<br>");

        if($attachment && file_exists($attachment)){
            $resultXML = file_get_contents($resultPath);
            //$resultXML = str_replace($attachment64, $toReplace, $resultXML);
            file_put_contents($resultPath,$resultXML); // заменили файл на ссылку назад (возмжно, лишнее)
        }
    }

    public function xopCreate($archivePath, $appeal = null, $insdata = [])
    {
        $source = '/var/www/admkrsk/common/config/template_attachment_ref.xml';
        $xmlPath = '/var/www/admkrsk/frontend/runtime/tmp/signed'.time().'.xml';
        $output = '/var/www/admkrsk/frontend/runtime/tmp/tosend'.time().'.txt';
        //$attachment = Yii::getAlias('@app').'/assets/6995_req_7a06c1c5-0218-4672-a6eb-7ef46529803e.zip';
        $attachment = $archivePath;

        $this->signServiceXML($source, $xmlPath, $attachment, $appeal, $insdata);

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

        return file_put_contents($output, $result)?$output:false;
    }

    protected function fillRequestTemplate(array $params)
    {
        $placeholders = [
          '{service name}', // reestr_number
        ];

        $appDataItems = [];


        $messageDataItems = [];

    }


    protected $serviceRequestTemplate = <<<SERVICE
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:int="http://intertrust.ru/" xmlns:rev="http://smev.gosuslugi.ru/rev120315" xmlns:admkrsk="http://smev.admkrsk.ru/v1.0">
  <soapenv:Header></soapenv:Header>
  <soapenv:Body wsu:Id="BodyId-F01F22D69F264C8CB33F8E5C95DDE883" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
    <int:Input_03_00_004FL>
      <rev:Message>
        <rev:Sender>
          <rev:Code>OSAK01241</rev:Code>
          <rev:Name>Официальный сайт администрации города Красноярска</rev:Name>
        </rev:Sender>
        <rev:Recipient>
          <rev:Code>236402241</rev:Code>
          <rev:Name>Электронный документооборот администрации города Красноярска</rev:Name>
        </rev:Recipient>
        <rev:Originator>
          <rev:Code>OSAK01241</rev:Code>
          <rev:Name>Официальный сайт администрации города Красноярска</rev:Name>
        </rev:Originator>
        <rev:ServiceName>{target-reestr_number}</rev:ServiceName>
        <rev:TypeCode>GSRV</rev:TypeCode>
        <rev:Status>REQUEST</rev:Status>
        <rev:Date>{date}</rev:Date>
        <rev:ExchangeType>1</rev:ExchangeType>
        <rev:ServiceCode>{target-service_code}</rev:ServiceCode>
        <rev:CaseNumber>{case_number}</rev:CaseNumber>
      </rev:Message>
      <rev:MessageData>
        <rev:AppData>
          <OrderID>{order_id}</OrderID>
          <ServiceCode>{target-reestr_number}</ServiceCode>
          <ServiceTarget>{target-name}</ServiceTarget>
          <ServicePlace>Город</ServicePlace>
          <DocDate>{docdate}</DocDate>
          <FL_FIO LastName="{surname}" FirstName="{name}" MiddleName="{parental_name}" />
          <FL_SNILS>{snils}</FL_SNILS>
          <FL_PASSPORT PassportSeriesAndNum="{passport_serie} {passport_number}" PassportWhoIssued="{passport_issued}" PassportWhenIssued="{passport_date}" />
          <ADR ADR_Country="{addr_country}" ADR_Region_Code="{addr_region_code}" ADR_Region="{addr_region}" ADR_City_Code="{addr_city_code}" ADR_City="{addr_city}" ADR_City_District="{addr_district}" ADR_Street_Code="{addr_street_code}" ADR_Street="{addr_street}" ADR_House="{addr_house}" ADR_Zip="{addr_zip}" />
          <FL_ContactInfo Tel="{phone}" />
          <FL_SUBJECT>{subject_text}</FL_SUBJECT>
          <FIELDS>
            {fields_list}
          </FIELDS>
          <AuthorProfile ConfidenceLevel="{confidence_level}" Login="{esialogin}" RequestRegNum="{regnum}" />
        </rev:AppData>
        <rev:AppDocument>
          <rev:RequestCode>{request_code}</rev:RequestCode>
          <rev:Reference>
            <xop:Include href="cid:{archive_cid}" xmlns:xop="http://www.w3.org/2004/08/xop/include" />
          </rev:Reference>
          <rev:DigestValue>{digest}</rev:DigestValue>
        </rev:AppDocument>
      </rev:MessageData>
    </int:Input_03_00_004FL>
  </soapenv:Body>
</soapenv:Envelope>
SERVICE;


    protected $serviceTestTemplate = <<<SERVICE_A
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:int="http://intertrust.ru/" xmlns:rev="http://smev.gosuslugi.ru/rev120315" xmlns:admkrsk="http://smev.admkrsk.ru/v1.0">
  <soapenv:Header></soapenv:Header>
  <soapenv:Body wsu:Id="BodyId-F01F22D69F264C8CB33F8E5C95DDE883" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
    <int:Input_06_01_004FL>
      <rev:Message>
        <rev:Sender>
          <rev:Code>OSAK01241</rev:Code>
          <rev:Name>Официальный сайт администрации города Красноярска</rev:Name>
        </rev:Sender>
        <rev:Recipient>
          <rev:Code>236402241</rev:Code>
          <rev:Name>Электронный документооборот администрации города Красноярска</rev:Name>
        </rev:Recipient>
        <rev:Originator>
          <rev:Code>OSAK01241</rev:Code>
          <rev:Name>Официальный сайт администрации города Красноярска</rev:Name>
        </rev:Originator>
        <rev:ServiceName>06/01/006</rev:ServiceName>
        <rev:TypeCode>GSRV</rev:TypeCode>
        <rev:Status>REQUEST</rev:Status>
        <rev:Date>{date}</rev:Date>
        <rev:ExchangeType>1</rev:ExchangeType>
        <rev:ServiceCode>06/01/006</rev:ServiceCode>
        <rev:CaseNumber>06/01/006-000012</rev:CaseNumber>
      </rev:Message>
      <rev:MessageData>
      </rev:MessageData>
    </int:Input_06_01_006FL>
  </soapenv:Body>
</soapenv:Envelope>        
SERVICE_A;

    protected $serviceTestTemplate2 = <<<SERVICE2
<?xml version="1.0" encoding="UTF-8"?><S:Envelope xmlns:S="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:int="http://intertrust.ru/" xmlns:rgu="http://smev.admkrsk.ru/v1.0" xmlns:smev="http://smev.gosuslugi.ru/rev120315" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
	<S:Header>
		<wsse:Security S:actor="http://smev.gosuslugi.ru/actors/smev">
			<wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="SenderCertificate">MIIEKDCCAxSgAwIBAgIP/Dsg/1t1ikTWDpXY9RB2MAkGBSsOAwIdBQAwgZcxCzAJBgNVBAYTAlJV
MRswGQYDVQQIExJLcmFzbm95YXJza2l5IGtyYXkxFDASBgNVBAcTC0tyYXNub3lhcnNrMSgwJgYD
VQQKEx9LcmFzbm95YXJzayBDaXR5IEFkbWluaXN0cmF0aW9uMQwwCgYDVQQLEwNVSVMxHTAbBgNV
BAMTFEFETUtSU0stVEVTVC1ST09ULUNBMCAXDTEyMTIzMTE2MDAwMFoYDzIwOTkxMjMxMTYwMDAw
WjCBnjELMAkGA1UEBhMCUlUxGzAZBgNVBAgTEktyYXNub3lhcnNraXkga3JheTEUMBIGA1UEBxML
S3Jhc25veWFyc2sxKDAmBgNVBAoTH0tyYXNub3lhcnNrIENpdHkgQWRtaW5pc3RyYXRpb24xDDAK
BgNVBAsTA1VJUzEkMCIGA1UEAxMbQURNS1JTSy1URVNULVNFUlZJQ0UtRE9NSU5PMIGfMA0GCSqG
SIb3DQEBAQUAA4GNADCBiQKBgQDCqS/tr7GA/JU9aL4FRaPjTdeHqy7t5ljzSn3N8ImoazlTMc9K
OBpkEIMYbRXLjmDC4hp5WbG70sbWl8gS30gUJA4B0qV8VWMla+o9EV0637LxH3ft2y73NeBpevfd
KR19mRQzxbt9luvviKUxyvmB9P4Hl416ZeFCxiM79hq8swIDAQABo4HxMIHuMB0GA1UdJQQWMBQG
CCsGAQUFBwMBBggrBgEFBQcDAjCBzAYDVR0BBIHEMIHBgBB1PSQ9HtWaaZyMmaRvjSzCoYGaMIGX
MQswCQYDVQQGEwJSVTEbMBkGA1UECBMSS3Jhc25veWFyc2tpeSBrcmF5MRQwEgYDVQQHEwtLcmFz
bm95YXJzazEoMCYGA1UEChMfS3Jhc25veWFyc2sgQ2l0eSBBZG1pbmlzdHJhdGlvbjEMMAoGA1UE
CxMDVUlTMR0wGwYDVQQDExRBRE1LUlNLLVRFU1QtUk9PVC1DQYIQks/3fqy4eJVI52TwhiWDpjAJ
BgUrDgMCHQUAA4IBAQCckxwVxeT/79nhFUMrPuUX5sUrvymh1KFiMQ8KoLzhmB39ILQcWvr0qCAd
IoV6KxbhLDYjf9QXXjoEIhnXr7dJxt2pGSOuY+J1Q6NFFGX1pb/KOnDSUXCVwWQZksVQaKzm5Xgy
Qp4kiqiakp37UbYbz1sAOrVVJmbN+K5jpAsUW5LZ/hTaK+ukrtDd4EvP2afON0NwSVELXNzMkL2q
5WVCQZyQN9QIIQgFUF6ZqepCGB7MUJrOfWbbaw9tpvkuC68gQl5MPgKF8g7EAHl+p88mGdmw0Snn
MEG43RJhlISU/ToDVVxLND2gFNzht8qFVejo5s9BD/+n9gpjkBTqYqLz</wsse:BinarySecurityToken>
		<Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><SignedInfo><CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/><Reference URI="#body"><Transforms><Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/><DigestValue>IwBvY4/gC8YvPNidHb4A5kS5MAE=</DigestValue></Reference></SignedInfo><SignatureValue>LEw9S0blUwyUpDXr/Eac7jTmi3I+RBCF2wmgHz+B2LERSAPqC8cPiBvE7ePm+RnsW4ga8tvYqRh+
R/aHDSeJ7CeUFddSAbMdzbwLrV0AGimhG6KqSVplRBXCMgOyf7s7dqfgeoxaptoVK8CjKfVF94yi
pcSqEsXNiXqdNyzvk3Y=</SignatureValue><KeyInfo><wsse:SecurityTokenReference><wsse:Reference URI="#SenderCertificate" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3"/></wsse:SecurityTokenReference></KeyInfo></Signature></wsse:Security>
	</S:Header>
	<S:Body wsu:Id="body">
		<!-- Тело сообщения -->
	<v25:pushEventRequest xmlns:v25="http://idecs.atc.ru/orderprocessing/ws/eventservice/v25/">
      <smev:Message>
        <smev:Sender>
          <smev:Code>236402241</smev:Code>
          <smev:Name>Электронный документооборот администрации города Красноярска</smev:Name>
        </smev:Sender>
        <smev:Recipient>
          <smev:Code>OSAK01241</smev:Code>
          <smev:Name>Официальный сайт администрации города Красноярска</smev:Name>
        </smev:Recipient>
        <smev:Originator>
          <smev:Code>OSAK01241</smev:Code>
          <smev:Name>Официальный сайт администрации города Красноярска</smev:Name>
        </smev:Originator>
        <smev:ServiceName>IPGUEVENTSERVICE25</smev:ServiceName>
        <smev:TypeCode>GSRV</smev:TypeCode>
        <smev:Status>RESULT</smev:Status>
        <smev:Date>2019-08-20T19:01:42.937+07:00</smev:Date>
        <smev:ExchangeType>1</smev:ExchangeType>
        <smev:RequestIdRef/>
        <smev:OriginRequestIdRef/>
        <smev:ServiceCode>2400000010000000000</smev:ServiceCode>
        <smev:CaseNumber>7A06C1C502184672A6EB7EF46529803E</smev:CaseNumber>
        <smev:TestMsg/>
      </smev:Message>
      <smev:MessageData>
        <smev:AppData>
          <orderId>6995</orderId>
          <eventDate>2019-08-20</eventDate>
          <eventComment/>
          <eventAuthor/>
          <event>
            <orderStatusEvent>
              <statusCode>
                <techCode>2</techCode>
              </statusCode>
              <cancelAllowed>true</cancelAllowed>
              <sendMessageAllowed>true</sendMessageAllowed>            
            </orderStatusEvent>
          </event> 
          <!--extention-->
          <OriginRequestServiceCode>03/00/004</OriginRequestServiceCode>
          <OriginRequestServiceName>Предоставление информации из Реестра муниципальной собственности</OriginRequestServiceName>
          <OriginRequestDate>2019-08-20</OriginRequestDate>
          <OriginRequestPerson>физическое лицо</OriginRequestPerson>
          <orgRegNum/>
          <orgRegDate/>
          <orgDeadline/>
          <orgStatusCode/>
          <!--end extention-->
      </smev:AppData>
      </smev:MessageData>
    </v25:pushEventRequest></S:Body>
</S:Envelope>
SERVICE2;


    protected $appealTestTemplate3 = <<<APPEAL3
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:int="http://intertrust.ru/" xmlns:rev="http://smev.gosuslugi.ru/rev120315" xmlns:admkrsk="http://smev.admkrsk.ru/v1.0">
  <soapenv:Header><wsse:Security soapenv:actor="http://smev.gosuslugi.ru/actors/smev"><wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="CertId-D900910002794EBFABFC78D079C373B7">MIIEJzCCAxOgAwIBAgIQLRPgc19QHIJIfqrdlOJ/rjAJBgUrDgMCHQUAMIGXMQswCQYDVQQGEwJSVTEbMBkGA1UECBMSS3Jhc25veWFyc2tpeSBrcmF5MRQwEgYDVQQHEwtLcmFzbm95YXJzazEoMCYGA1UEChMfS3Jhc25veWFyc2sgQ2l0eSBBZG1pbmlzdHJhdGlvbjEMMAoGA1UECxMDVUlTMR0wGwYDVQQDExRBRE1LUlNLLVRFU1QtUk9PVC1DQTAgFw0xMjEyMzExNjAwMDBaGA8yMDk5MTIzMTE2MDAwMFowgZwxCzAJBgNVBAYTAlJVMRswGQYDVQQIExJLcmFzbm95YXJza2l5IGtyYXkxFDASBgNVBAcTC0tyYXNub3lhcnNrMSgwJgYDVQQKEx9LcmFzbm95YXJzayBDaXR5IEFkbWluaXN0cmF0aW9uMQwwCgYDVQQLEwNVSVMxIjAgBgNVBAMTGUFETUtSU0stVEVTVC1TRVJWSUNFLVNJVEUwgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAN1NinggsY6Q6EcaWJLerxu9a4IyaEDejwcDWxuhYkBVYVsbFDtNu5cYWIZH0gLmm3KlnVYwV2jSTQ6o0r1zTKQcwvqd1PboXFUJzFY9jrnEUGNHsUmZH7vFM4jRGZFAVlapmBCOqSI29PiosAzYbasf8XR5Wn9cRJ5vooo7QzS5AgMBAAGjgfEwge4wHQYDVR0lBBYwFAYIKwYBBQUHAwEGCCsGAQUFBwMCMIHMBgNVHQEEgcQwgcGAEHU9JD0e1ZppnIyZpG+NLMKhgZowgZcxCzAJBgNVBAYTAlJVMRswGQYDVQQIExJLcmFzbm95YXJza2l5IGtyYXkxFDASBgNVBAcTC0tyYXNub3lhcnNrMSgwJgYDVQQKEx9LcmFzbm95YXJzayBDaXR5IEFkbWluaXN0cmF0aW9uMQwwCgYDVQQLEwNVSVMxHTAbBgNVBAMTFEFETUtSU0stVEVTVC1ST09ULUNBghCSz/d+rLh4lUjnZPCGJYOmMAkGBSsOAwIdBQADggEBABkdZ9naW0M+AW9XAWTLTApUrju/gRevzAIY8XbwSkmIAO0ljXQeR0RFeYF5AI0+70w/lFYPyTNFmJh5GAgdNUIPsWbKI4WE8dZKUY91jkj/U9fX4vLMFK6rKrF9ZXwyS3Nxs0QTgloPZSPibd/OlYuzrZc6v/RMxS8ezRpZ9qS2GtB+I4w7CudGDD32uaWWMnZQRwH2wtciRk+p3vCylyvGtEoKRc4HWMjCtf0/BcglxXEWyy7K/frzq0YJ8w5qgv7lSrFE/2jYQCrVONPqTNEcFkap/ToZMv5jRxXoJN7G7iRqWPu29M4XBnBBv7MEDNHa/gOceroYoVOZ0rSZ444=</wsse:BinarySecurityToken><ds:Signature><SignedInfo xmlns="http://www.w3.org/2000/09/xmldsig#"><CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#" /><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1" /><Reference URI="#BodyId-A89BD80520AB4D298B590016F6414312"><Transforms><Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#" /></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1" /><DigestValue>YXV0gWwbeRAjKWWmyu799xcndIw=</DigestValue></Reference></SignedInfo><SignatureValue xmlns="http://www.w3.org/2000/09/xmldsig#">U9n3oHJdfdRCoxXvfhaZv1KdLVgyi6+Fu4SVO5Ni1Kdt4R0iiWKLubmez/Fug63F668NfLu90+C/eRia3uWy5WMN+NED71AM3r1dpcDG65h7gJUjaZQrD/gMVQkeK1XUc63aCmBMXtgg5CGhgoep6guTCldXE85QwIWJSI4sZAc=</SignatureValue><ds:KeyInfo><wsse:SecurityTokenReference><wsse:Reference URI="#CertId-D900910002794EBFABFC78D079C373B7" /></wsse:SecurityTokenReference></ds:KeyInfo></ds:Signature></wsse:Security></soapenv:Header>
  <soapenv:Body wsu:Id="BodyId-A89BD80520AB4D298B590016F6414312" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
    <int:Input_UserRequestFL>
      <rev:Message>
        <rev:Sender>
          <rev:Code>OSAK01241</rev:Code>
          <rev:Name>Официальный сайт администрации города Красноярска</rev:Name>
        </rev:Sender>
        <rev:Recipient>
          <rev:Code>236402241</rev:Code>
          <rev:Name>Электронный документооборот администрации города Красноярска</rev:Name>
        </rev:Recipient>
        <rev:Originator>
          <rev:Code>OSAK01241</rev:Code>
          <rev:Name>Официальный сайт администрации города Красноярска</rev:Name>
        </rev:Originator>
        <rev:ServiceName>UserRequest</rev:ServiceName>
        <rev:TypeCode>GSRV</rev:TypeCode>
        <rev:Status>REQUEST</rev:Status>
        <rev:Date>2019-08-21T00:30:21.6588711+07:00</rev:Date>
        <rev:ExchangeType>1</rev:ExchangeType>
        <rev:ServiceCode>2400000010000000000</rev:ServiceCode>
        <rev:CaseNumber>CCC15B6D41BB47B1B0B0B5DBC97D13CB</rev:CaseNumber>
      </rev:Message>
      <rev:MessageData>
        <rev:AppData>
          <OrderID>61058</OrderID>
          <UserRequest xsi:noNamespaceSchemaLocation="UserRequests.xsd">
            <ID>ВП-2019-005116</ID>
            <DepName>Администрация города Красноярска</DepName>
            <typeRequest>обращение</typeRequest>
            <classRequest>жалоба</classRequest>
            <topic>Коммунальное хозяйство</topic>
            <question>  *   Подключение воды п.Солонцы   *  </question>
            <questionText>Тема: Подключение воды п.Солонцы Содержимое: Здравствуйте! Нам, как многодетной семье, в 2016г выделили земельный участок в пос. Солонцы. Адрес ул. Рождественская 34, кадастровый номер 24:11:0290105:5438. Но до сих пор на участок не проведена вода, и сроки возможности ее проведения не определены. Вести ЛПХ без воды не возможно, привозная вода стоит дорого. Просьба сообщить сроки подключения водоснабжения участка. На всем поле, где выделены участки, участки для многодетных семей. Привозная вода стоить 380р куб. Таких средств, чтоб поливать огород, у нас нет.</questionText>
            <response>1</response>
            <nameLast>Могильникова</nameLast>
            <nameFirst>Татьяна</nameFirst>
            <nameMiddle>Николаевна</nameMiddle>
            <category>Многодетная семья</category>
            <Phone>8-902-991-2983</Phone>
            <EMail>tanyamog2@mail.ru</EMail>
          </UserRequest>
          <AuthorProfile ConfidenceLevel="5" RequestRegNum="ВП-2019-005116" />
        </rev:AppData>
        <rev:AppDocument>
          <rev:RequestCode>req_ccc15b6d-41bb-47b1-b0b0-b5dbc97d13cb</rev:RequestCode>
          <rev:Reference>
            <xop:Include href="cid:5aeaa450-17f0-4484-b845-a8480c363444" xmlns:xop="http://www.w3.org/2004/08/xop/include" />
          </rev:Reference>
          <rev:DigestValue>S/3WvbzesLpRqxGzbO6AZvJN0tA=</rev:DigestValue>
        </rev:AppDocument>
      </rev:MessageData>
    </int:Input_UserRequestFL>
  </soapenv:Body>
</soapenv:Envelope>
APPEAL3;
}
<?php
// Класс для работы с СЭД


namespace common\models;

use Yii;
use yii\base\Model;


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
                'SOAPAction:urn:#Operation_02_00_016FL'
            ];

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            $server_output = curl_exec($curl);

            print_r($server_output);
            die();

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

    public function getServiceMessage($raw)
    {

    }

    public function generateArchive($guid, $attachments = [], $formFile = false)
    {
        $zip_path = Yii::getAlias('@runtime') . $this->path . 'req_' . $guid. '.zip';


        $zip = new \ZipArchive();

        if (is_file($zip_path))
            unlink($zip_path);

        $filesToUnlink = [];

        if ($zip->open($zip_path,\ZIPARCHIVE::CREATE) === TRUE)
        {
            foreach ($attachments as $key => $att)
            {
                $tfile = file_get_contents($att);
                $ext = explode(".", $att);

                $ext = end($ext);
                if($ext == 'zip')
                    $ext = 'gz';

                $tpath = Yii::getAlias('@runtime') . $this->path . "req_" . $guid . "-$key-." . $ext;

                file_put_contents($tpath, $tfile);

                if (is_file($tpath)) {
                    $zip->addFile($tpath, 'req_' . $guid . "-$key-." . $ext);
                    $filesToUnlink[] = $tpath;
                }
            }

            if(file_exists($formFile))
            {
                $tfile = file_get_contents($formFile);
                $docPath = Yii::getAlias('@runtime') . $this->path . "req_" . $guid . ".docx";

                file_put_contents($docPath, $tfile);

                if (is_file($docPath)) {
                    $zip->addFile($docPath, 'req_' . $guid . ".docx");
                    $filesToUnlink[] = $docPath;
                }
            }

            $zip->close();

            foreach ($filesToUnlink as $ufile)
                if(is_file($ufile)) unlink($ufile);

            return $zip_path;
        }
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


    protected $serviceTestTemplate = <<<SERVICE
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
SERVICE;

    protected $serviceTestTemplate2 = <<<SERVICE2
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:int="http://intertrust.ru/" xmlns:rev="http://smev.gosuslugi.ru/rev120315" xmlns:admkrsk="http://smev.admkrsk.ru/v1.0">
  <soapenv:Header><wsse:Security soapenv:actor="http://smev.gosuslugi.ru/actors/smev"><wsse:BinarySecurityToken EncodingType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary" ValueType="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-x509-token-profile-1.0#X509v3" wsu:Id="CertId-302C99C4461A4D60B6DD178B83E36B22">MIIEJzCCAxOgAwIBAgIQLRPgc19QHIJIfqrdlOJ/rjAJBgUrDgMCHQUAMIGXMQswCQYDVQQGEwJSVTEbMBkGA1UECBMSS3Jhc25veWFyc2tpeSBrcmF5MRQwEgYDVQQHEwtLcmFzbm95YXJzazEoMCYGA1UEChMfS3Jhc25veWFyc2sgQ2l0eSBBZG1pbmlzdHJhdGlvbjEMMAoGA1UECxMDVUlTMR0wGwYDVQQDExRBRE1LUlNLLVRFU1QtUk9PVC1DQTAgFw0xMjEyMzExNjAwMDBaGA8yMDk5MTIzMTE2MDAwMFowgZwxCzAJBgNVBAYTAlJVMRswGQYDVQQIExJLcmFzbm95YXJza2l5IGtyYXkxFDASBgNVBAcTC0tyYXNub3lhcnNrMSgwJgYDVQQKEx9LcmFzbm95YXJzayBDaXR5IEFkbWluaXN0cmF0aW9uMQwwCgYDVQQLEwNVSVMxIjAgBgNVBAMTGUFETUtSU0stVEVTVC1TRVJWSUNFLVNJVEUwgZ8wDQYJKoZIhvcNAQEBBQADgY0AMIGJAoGBAN1NinggsY6Q6EcaWJLerxu9a4IyaEDejwcDWxuhYkBVYVsbFDtNu5cYWIZH0gLmm3KlnVYwV2jSTQ6o0r1zTKQcwvqd1PboXFUJzFY9jrnEUGNHsUmZH7vFM4jRGZFAVlapmBCOqSI29PiosAzYbasf8XR5Wn9cRJ5vooo7QzS5AgMBAAGjgfEwge4wHQYDVR0lBBYwFAYIKwYBBQUHAwEGCCsGAQUFBwMCMIHMBgNVHQEEgcQwgcGAEHU9JD0e1ZppnIyZpG+NLMKhgZowgZcxCzAJBgNVBAYTAlJVMRswGQYDVQQIExJLcmFzbm95YXJza2l5IGtyYXkxFDASBgNVBAcTC0tyYXNub3lhcnNrMSgwJgYDVQQKEx9LcmFzbm95YXJzayBDaXR5IEFkbWluaXN0cmF0aW9uMQwwCgYDVQQLEwNVSVMxHTAbBgNVBAMTFEFETUtSU0stVEVTVC1ST09ULUNBghCSz/d+rLh4lUjnZPCGJYOmMAkGBSsOAwIdBQADggEBABkdZ9naW0M+AW9XAWTLTApUrju/gRevzAIY8XbwSkmIAO0ljXQeR0RFeYF5AI0+70w/lFYPyTNFmJh5GAgdNUIPsWbKI4WE8dZKUY91jkj/U9fX4vLMFK6rKrF9ZXwyS3Nxs0QTgloPZSPibd/OlYuzrZc6v/RMxS8ezRpZ9qS2GtB+I4w7CudGDD32uaWWMnZQRwH2wtciRk+p3vCylyvGtEoKRc4HWMjCtf0/BcglxXEWyy7K/frzq0YJ8w5qgv7lSrFE/2jYQCrVONPqTNEcFkap/ToZMv5jRxXoJN7G7iRqWPu29M4XBnBBv7MEDNHa/gOceroYoVOZ0rSZ444=</wsse:BinarySecurityToken><ds:Signature><SignedInfo xmlns="http://www.w3.org/2000/09/xmldsig#"><CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#" /><SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1" /><Reference URI="#BodyId-7A31D91EE40D4E5E9C114FB57B0AD1F1"><Transforms><Transform Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#" /></Transforms><DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1" /><DigestValue>w3u0ag/+Oai8axu8aOtd7ip/yfQ=</DigestValue></Reference></SignedInfo><SignatureValue xmlns="http://www.w3.org/2000/09/xmldsig#">RExA/vE4ReShykSx13s+h5FLebETzlp9QGFHj18rSd5dYo5XvIKNq1NM8nvkdSyR0GPCIG+q0p8WTOXZYKIZx9xsu68kGurQoLr7sGkCw8+wtlRVzCZ2P2Jr0ZyT0KeHcaY1QmJNYmT2FBEUcEnNluLJDYhvI/N4aXoB5iPyfc4=</SignatureValue><ds:KeyInfo><wsse:SecurityTokenReference><wsse:Reference URI="#CertId-302C99C4461A4D60B6DD178B83E36B22" /></wsse:SecurityTokenReference></ds:KeyInfo></ds:Signature></wsse:Security></soapenv:Header>
  <soapenv:Body wsu:Id="BodyId-7A31D91EE40D4E5E9C114FB57B0AD1F1" xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
    <int:Input_02_00_016UL>
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
        <rev:ServiceName>02/00/016</rev:ServiceName>
        <rev:TypeCode>GSRV</rev:TypeCode>
        <rev:Status>REQUEST</rev:Status>
        <rev:Date>2019-08-13T17:20:25.0284502+07:00</rev:Date>
        <rev:ExchangeType>1</rev:ExchangeType>
        <rev:ServiceCode>2400000010000000000</rev:ServiceCode>
        <rev:CaseNumber>A98D2C7518C44C5DA8CE81A3920135A2</rev:CaseNumber>
      </rev:Message>
      <rev:MessageData>
        <rev:AppData>
          <OrderID>6978</OrderID>
          <ServiceCode>02/00/016</ServiceCode>
          <ServiceTarget>О предоставлении сведений из информационной системы обеспечения градостроительной деятельности</ServiceTarget>
          <ServicePlace>Город</ServicePlace>
          <DocDate>2019-08-13</DocDate>
          <UL_Name>АГЕНТСТВО ПО УПРАВЛЕНИЮ ГОСУДАРСТВЕННЫМ ИМУЩЕСТВОМ КРАСНОЯРСКОГО КРАЯ</UL_Name>
          <UL_INN>2466133722</UL_INN>
          <UL_OGRN>1052466191580</UL_OGRN>
          <UL_ADR1 ADR_Country="Россия" ADR_Region_Code="2400000000000" ADR_Region="Красноярский край" ADR_City="г Красноярск" ADR_Street="ул Ленина" ADR_House="123" ADR_Build="а" ADR_Zip="660017" />
          <UL_ADR2 ADR_Country="Россия" ADR_Region_Code="2400000000000" ADR_Region="Красноярский край" ADR_City="г Красноярск" ADR_Street="ул Ленина" ADR_House="123А" ADR_Zip="660017" />
          <TrustedPerson LastName="ИВАНОВ" FirstName="ИВАН" MiddleName="ИВАНОВИЧ" />
          <UL_ChiefJob>главный специалист</UL_ChiefJob>
          <UL_ContactInfo Tel="211-19-88" />
          <UL_SUBJECT>TEST REQUEST</UL_SUBJECT>
          <FIELDS>
            <Field FLD_Name="Без имени" FLD_Type="string" FLD_Value="24:50:0000000:154957" />
            <Field FLD_Name="Без имени" FLD_Type="string" FLD_Value="" />
            <Field FLD_Name="Без имени" FLD_Type="string" FLD_Value="" />
            <Field FLD_Name="Без имени" FLD_Type="string" FLD_Value="" />
            <Field FLD_Name="Без имени" FLD_Type="string" FLD_Value="" />
            <Field FLD_Name="Результат предоставления Услуги прошу" FLD_Code="выдать на руки" FLD_Type="string" FLD_Value="">false</Field>
            <Field FLD_Name="Результат предоставления Услуги прошу" FLD_Code="направить почтой" FLD_Type="string" FLD_Value="">false</Field>
            <Field FLD_Name="Результат предоставления Услуги прошу" FLD_Code="предоставить в электронной форме" FLD_Type="string" FLD_Value="Да">true</Field>
          </FIELDS>
          <AuthorProfile ConfidenceLevel="Подтвержденная учетная запись ЕСИА органа исполнительной власти, аутентификация по логину и паролю" Login="esia#1002379405@gosuslugi.ru" RequestRegNum="02/00/016-0343" />
        </rev:AppData>
        <rev:AppDocument>
          <rev:RequestCode>req_a98d2c75-18c4-4c5d-a8ce-81a3920135a2</rev:RequestCode>
          <rev:Reference>
            <xop:Include href="cid:5aeaa450-17f0-4484-b845-a8480c363444" xmlns:xop="http://www.w3.org/2004/08/xop/include" />
          </rev:Reference>
          <rev:DigestValue>rZrYWs7hggTvx/YzZ7FHE7BuYdA=</rev:DigestValue>
        </rev:AppDocument>
      </rev:MessageData>
    </int:Input_02_00_016UL>
  </soapenv:Body>
</soapenv:Envelope>
SERVICE2;


}
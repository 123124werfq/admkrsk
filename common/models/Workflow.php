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



}
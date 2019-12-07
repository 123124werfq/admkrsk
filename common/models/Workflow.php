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

    private $debug = true;

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

    private function sendPost($message, $url)
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

        $this->generateServiceRequest($record);

        if($this->debug)
            return true;

        return $this->sendPost($serviceRecord->toString(), $this->sendServiceURL);
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

        var_dump($recordItems);
        var_dump($targetItems);

        die();
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
        <rev:ServiceName>03/00/004</rev:ServiceName>
        <rev:TypeCode>GSRV</rev:TypeCode>
        <rev:Status>REQUEST</rev:Status>
        <rev:Date>2019-08-20T18:32:39.4965852+07:00</rev:Date>
        <rev:ExchangeType>1</rev:ExchangeType>
        <rev:ServiceCode>2400000010000000000</rev:ServiceCode>
        <rev:CaseNumber>7A06C1C502184672A6EB7EF46529803E</rev:CaseNumber>
      </rev:Message>
      <rev:MessageData>
        <rev:AppData>
          <OrderID>6995</OrderID>
          <ServiceCode>03/00/004</ServiceCode>
          <ServiceTarget>выписка на жилое помещение</ServiceTarget>
          <ServicePlace>Город</ServicePlace>
          <DocDate>2019-08-20</DocDate>
          <FL_FIO LastName="Потапов" FirstName="Александр" MiddleName="Владимирович" />
          <FL_SNILS>148-049-977 00</FL_SNILS>
          <FL_PASSPORT PassportSeriesAndNum="0408 №650182" PassportWhoIssued="Территориальным пунктом УФМС России по Красноярскому краю в Тасеевском районе" PassportWhenIssued="2008-11-05" />
          <ADR ADR_Country="Россия" ADR_Region_Code="2400000000000" ADR_Region="Красноярский край" ADR_City_Code="2400000100000" ADR_City="Красноярск г" ADR_City_District="Советский район" ADR_Street_Code="240000010000322" ADR_Street="Краснодарская ул" ADR_House="14" ADR_Zip="660005" />
          <FL_ContactInfo Tel="7 (904) 897-45-56" />
          <FL_SUBJECT>	Прошу предоставить выписку из Реестра муниципальной собственности на
	 квартира
	расположенное по адресу:
	улица, г. Красноярск Краснодарская
	номер дома (строения/литера - при наличии) 14
	номер жилого помещения в доме 11

Способ получения выписки:
Лично -
Почтовым отправлением по адресу -
Направить в электронном виде - Да
Выдать через МФЦ (в случае, если заявление было подано в МФЦ) -

Дата и время запроса услуги на Сайте: 20.08.2019 18:32.
Регистрационный номер запроса услуги на Сайте: 03/00/004-0438.
Логин пользователя на Сайте: esia#1056781441@gosuslugi.ru.
Уровень достоверности: Подтвержденная учетная запись ЕСИА физического лица, аутентификация по логину и паролю.</FL_SUBJECT>
          <FIELDS>
            <Field FLD_Name="Без имени" FLD_Type="string" FLD_Value="квартира" />
            <Field FLD_Name="Без имени" FLD_Type="string" FLD_Value="Краснодарская" />
            <Field FLD_Name="Без имени" FLD_Type="string" FLD_Value="14" />
            <Field FLD_Name="Без имени" FLD_Type="string" FLD_Value="11" />
            <Field FLD_Name="Без имени" FLD_Type="string" FLD_Value="здание" />
            <Field FLD_Name="Без имени" FLD_Type="string" FLD_Value="прилагается" />
            <Field FLD_Name="Способ получения выписки" FLD_Code="Лично" FLD_Type="string" FLD_Value="">false</Field>
            <Field FLD_Name="Способ получения выписки" FLD_Code="Почтовым отправлением по адресу" FLD_Type="string" FLD_Value="">false</Field>
            <Field FLD_Name="Способ получения выписки" FLD_Code="Направить в электронном виде" FLD_Type="string" FLD_Value="Да">true</Field>
            <Field FLD_Name="Способ получения выписки" FLD_Code="Выдать через МФЦ (в случае, если заявление было подано в МФЦ)" FLD_Type="string" FLD_Value="">false</Field>
          </FIELDS>
          <AuthorProfile ConfidenceLevel="Подтвержденная учетная запись ЕСИА физического лица, аутентификация по логину и паролю" Login="esia#1056781441@gosuslugi.ru" RequestRegNum="03/00/004-0438" />
        </rev:AppData>
        <rev:AppDocument>
          <rev:RequestCode>req_7a06c1c5-0218-4672-a6eb-7ef46529803e</rev:RequestCode>
          <rev:Reference>
            <xop:Include href="cid:5aeaa450-17f0-4484-b845-a8480c363444" xmlns:xop="http://www.w3.org/2004/08/xop/include" />
          </rev:Reference>
          <rev:DigestValue>DNmdDWA7PdxrjvHOVmmUUOpUf/M=</rev:DigestValue>
        </rev:AppDocument>
      </rev:MessageData>
    </int:Input_03_00_004FL>
  </soapenv:Body>
</soapenv:Envelope>
SERVICE;



}
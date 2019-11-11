<?php
// Класс для работы с СЭД


namespace common\models;

use Yii;
use yii\base\Model;


class Workflow extends Model
{

    private $sendServiceURL = 'http://10.24.0.201/WSSiteRSA';
    private $sendAppealURL = 'http://10.24.0.201/WSVRRSA';

    public $error;

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

    public function sendServiceMessage($serviceRecord)
    {
        return $this->sendPost($serviceRecord->toString(), $this->sendServiceURL);
    }

    public function getServiceMessage($raw)
    {

    }


}
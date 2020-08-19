<?php

namespace console\controllers;

use Yii;
use SimpleXMLElement;
use yii\console\Controller;

use common\models\ServiceAppeal;
use common\models\ServiceAppealState;


class ServiceController extends Controller
{

    public function actionIndex()
    {

    }

    static protected function xmlToArray($xml)
    {
        $res = false;
        $response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", trim($xml));
        $xml = new SimpleXMLElement($response);
        $body = $xml->xpath('//SBody')[0];
        $res = json_decode(json_encode((array)$body), TRUE);
        return $res;
    }

    public function actionParse($path)
    {
        if(!file_exists($path))
        {
            echo "File not found";
            return;
        }

        $raw = file_get_contents($path);

        $parts = explode("+0700:", $raw);

        foreach ($parts as $key => $part) {

            if(strpos($part, "?xml"))
            {

                $part = substr($part, (strpos($part, "\n")+1));
                $part = substr($part, 0, strrpos($part, "\n"));

                $xmlArray = self::xmlToArray($part);

                $caseNum =  $xmlArray['v25pushEventRequest']['smevMessage']['smevCaseNumber'];
                $statusInfo = $xmlArray['v25pushEventRequest']['smevMessageData']['smevAppData'];
                $statusCode = $xmlArray['v25pushEventRequest']['smevMessageData']['smevAppData']['event']['orderStatusEvent']['statusCode']['techCode'];

                $apppeal = ServiceAppeal::find()->where("number_internal='$caseNum'")->one();

                if(!$apppeal)
                {
                    echo "Запрос $caseNum - $statusCode не найден\n";
                }
                else
                {
                    $unixDate = strtotime($statusInfo['eventDate']);
                    $as = ServiceAppealState::find()->where("id_appeal=$apppeal->id_appeal")->andWhere("state='$statusCode'")->andWhere("date=$unixDate")->one();

                    if(!$as)
                    {
                        $as = new ServiceAppealState;
                        $as->id_appeal = $apppeal->id_appeal;
                        $as->state = $statusCode;
                        $as->date = $unixDate;
                        if($as->save())
                            echo "Запрос $caseNum - $statusCode сохранён\n";
                        else
                            echo "Запрос $caseNum - $statusCode сохранить не удалось\n";
                    }
                }

                /*
                echo $caseNum;
                echo "\n";
                echo $statusCode;
                print_r($statusInfo);
                */
            }
        }

    }

}

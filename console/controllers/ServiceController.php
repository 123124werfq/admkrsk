<?php

namespace console\controllers;

use Yii;
use SimpleXMLElement;
use yii\console\Controller;

use common\models\Service;
use common\models\ServiceAppeal;
use common\models\ServiceAppealState;
use common\models\Collection;

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

    public function actionOffice()
    {
        $sql = "SELECT * FROM tmp_import_offices";
        $relation = Yii::$app->db->createCommand($sql)->queryAll();

        $collection = Collection::find()->where(['id_collection'=>498])->one();

        $datas = $collection->getData([],true);

        $sql = "DELETE FROM servicel_collection_firm";
        Yii::$app->db->createCommand($sql)->execute();

        $search = [];
        foreach ($datas as $id_record => $data) {
            $search[(string)$data['id_office']] = $id_record;
        }

        $services = Service::find()->indexBy('reestr_number')->all();

        foreach ($relation as $key => $data)
        {
            if (isset($services[$data['service']]) && isset($search[$data['office']]))
            {
                $cnt = Yii::$app->db->createCommand('SELECT count(*) FROM servicel_collection_firm WHERE id_service = '.$services[$data['service']]->id_service.' AND id_record = '.$search[$data['office']])->queryScalar();

                if ($cnt==0)
                    Yii::$app->db->createCommand()->insert('servicel_collection_firm',[
                        'id_service'=>$services[$data['service']]->id_service,
                        'id_record'=>$search[$data['office']],
                    ])->execute();
            }
        }
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

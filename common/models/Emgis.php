<?php
// Класс для работы с ЕМГИС


namespace common\models;

use Yii;
use yii\base\Model;
use GuzzleHttp\Client;

//use Selective\XmlDSig\DigestAlgorithmType;
//use Selective\XmlDSig\XmlSigner;
//use XmlDsig\XmlDigitalSignature;

class Emgis extends Model
{

    private $url = "https://web-gis.admkrsk.ru/ActionServlet";
    private $loginQuery = "action=configurator&mode=logon&j_gee_metamodel=mm_kras_property&j_gee_username=web_public_remedy&j_gee_password=F9ubkBkbWkuYWR";
    private $credenitials = [
        "j_gee_username" => "web_public_remedy",
        "j_gee_password" => "F9ubkBkbWkuYWR"
    ];

    private function request($params)
    {
        $url = $this->url . "?" . http_build_query($params);
        var_dump($url); 
        try{
            $client = new \GuzzleHttp\Client();
            $jar = new \GuzzleHttp\Cookie\CookieJar;
            $response = $client->request('GET', $this->url . "?" . $this->loginQuery, [
                'cookies' => $jar
            ]);

            $response = $client->request('GET', $url , [
                'cookies' => $jar
            ]);

            if($response->getStatusCode() != 200)
                return ['error' => $response->getStatusCode()];

            $content = $response->getBody();

echo($content);
            $res = json_decode($content, TRUE);

            return $res;
        }
        catch (\Exception $e) {
            //echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
            return ['error' => $e->getMessage()];
        }
        
    }

    private function Classificator($result)
    {
        if(!isset($result['d']['results']))
            return $result;

        $output = [];
        foreach ($result['d']['results'] as $item) {
            $output[] = $item['ClsName'] ?? "";
        }

        return $output;
    }

    private function FieldsList($result)
    {
        if(!isset($result['d']['results']))
            return $result;

        $output = [];
        foreach ($result['d']['results'] as $item) {
            if(!isset($item['InternalName']))
                continue;

            $output[$item['InternalName']] = $item['Title'] ?? $item['InternalName'];
        }

        return $output;

    }

    private function ItemsList($result, $query = [])
    {
        $output = [];

        foreach ($result['d']['results'] as $item) {
            $output[] = [];
            foreach ($item as $column => $row) {
                $output[$column] = $row;
            }
        }

        return $output;
    }



    // классификатор "категория земель"
    public function CategoryClassificator($raw = false)
    {
        $params = [
            "action" => "odata",
            "bankID" => 2,
            "r" => "FunCls1/items",
            "\$select" => "ClsName"
        ];

        $result = $this->request($params);

        if($raw)
            return $result;

        return $this->Classificator($result);
    }

    // классификатор "вид ограничения / обременения"
    public function EncumbranceClassificator($raw = false)
    {
        $params = [
            "action" => "odata",
            "bankID" => 2,
            "r" => "Right/items",
            "\$select" => "ClsName",
            "\$filter" => urldecode("Right_ID%20eq%2018%20or%20Right_ID%20eq%2030")
        ];

        $result = $this->request($params);

        if($raw)
            return $result;

        return $this->Classificator($result);

    }    

    // классификатор "наименование иного вещного права"
    public function RightClassificator($raw = false)
    {
        $params = [
            "action" => "odata",
            "bankID" => 2,
            "r" => "Right/items",
            "\$select" => "ClsName",
            "\$filter" => urldecode("Right_ID%20eq%202%20or%20Right_ID%20eq%203%20or%20Right_ID%20eq%2020")
        ];

        $result = $this->request($params);

        if($raw)
            return $result;

        return $this->Classificator($result);
    }      

    // сведения о земельных участках
    public function Remedy65Request($query = [])
    {
        /*
        $params = [
            "action" => "odata",
            "bankID" => 2,
            "r" => "Remedy65/fields",
        ];

        $fields = $this->FieldsList($this->request($params));

        var_dump($fields);
        */

        $params = [
            "action" => "odata",
            "bankID" => 2,
            "r" => "Remedy65/items",
            "\$filter"=> "FunCls1_ClsName%20eq%20Земли%20населенных%20пунктов"
        ];

        $rows = $this->ItemsList($this->request($params));

        return $rows;
    }
}
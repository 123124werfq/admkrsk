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

    public $onpage = 20;

    private function request($params)
    {
        $url = $this->url . "?" . http_build_query($params);

        try{
            $client = new \GuzzleHttp\Client(['verify' => false ]);
            $jar = new \GuzzleHttp\Cookie\CookieJar;
            $response = $client->request('GET', $this->url . "?" . $this->loginQuery, [
                'cookies' => $jar
            ]);

            $response = $client->request('GET', $url , [
                'cookies' => $jar,
                'expect' => false
            ]);

            if($response->getStatusCode() != 200)
                return ['error' => $response->getStatusCode()];

            $content = $response->getBody();
            //var_dump($content);
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
        if(!isset($result['d']))
            return $result;

        $output = [];
        foreach ($result['d']['results'] as $item) {
            $output[] = $item['ClsName'] ?? $item['Terr_AllowType'] ?? "";
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

        if(!isset($result['d']))
            return $result;        

        foreach ($result['d']['results'] as $key => $item) {
            $output[$key] = [];
            foreach ($item as $column => $row) {
                $output[$key][$column] = $row;
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
    
    // классификатор "вид разрешенного использования"
    public function AllowedClassificator($raw = false)
    {
        $params = [
            "action" => "odata",
            "bankID" => 2,
            "r" => "Remedy65/items",
            "\$select" => "Terr_AllowType",
        ];

        $result = $this->request($params);

        if($raw)
            return $result;

        return $this->Classificator($result);
    }     

    private function InfoRequest($setname, $filter = "", $page=1)
    {
        $params = [
            "action" => "odata",
            "bankID" => 2,
            "r" => $setname."/fields",
        ];

        $fields = $this->FieldsList($this->request($params));

        $params = [
            "action" => "odata",
            "bankID" => 2,
            "r" => $setname."/items",
            "\$top" => $this->onpage
        ];

        if($page>1)
        {
            $params["\$skip"] = $this->onpage * ($page -1);
        }

        if(!empty($filter))
            $params = array_merge($params, ["\$filter"=> $filter]);

        $r = $this->request($params);


        $rows = $this->ItemsList($this->request($params));

        return ["fields" => $fields, "data" => $rows];
    }

    private function CountRequest($setname, $filter = "")
    {
        $params = [
            "action" => "odata",
            "bankID" => 2,
            "r" => $setname."/\$count",
        ];
        
        if(!empty($filter))
            $params = array_merge($params, ["\$filter"=> $filter]);

        //var_dump($params);die();

        $r = $this->request($params);

        return (int)$r;
    }    

    // сведения о земельных участках
    public function Remedy65Request($query = [], $page = 1)
    {
        if(isset($query['count']))
            return $this->CountRequest("Remedy65", $query['filter']??"");
        else
            return $this->InfoRequest("Remedy65", $query['filter']??"", $page);
    }    

    // сведения о зданиях 
    public function Remedy2BuildRequest($query = [], $page = 1)
    {
        if(isset($query['count']))
            return $this->CountRequest("Remedy2Build", $query['filter']??"");
        else
            return $this->InfoRequest("Remedy2Build", $query['filter']??"", $page);
    }

    // сведения о сооружениях 
    public function Remedy3Request($query = [], $page = 1)
    {
        if(isset($query['count']))
            return $this->CountRequest("Remedy3", $query['filter']??"");
        else
            return $this->InfoRequest("Remedy3", $query['filter']??"", $page);
    }

    // сведения о помещениях 
    public function Remedy2RoomRequest($query = [], $page = 1)
    {
        if(isset($query['count']))
            return $this->CountRequest("Remedy2Room", $query['filter']??"");
        else
            return $this->InfoRequest("Remedy2Room", $query['filter']??"", $page);
    }    

    // сведения об объектах незавершенного строительства 
    public function Remedy4Request($query = [], $page = 1)
    {
        if(isset($query['count']))
            return $this->CountRequest("Remedy4", $query['filter']??"");
        else
            return $this->InfoRequest("Remedy4", $query['filter']??"", $page);
    }

    // сведения о движимом имуществе 
    public function Remedy5Request($query = [])
    {
        return $this->InfoRequest("Remedy5");
    }  
    
    // сведения об акциях 
    public function Remedy7Request($query = [])
    {
        return $this->InfoRequest("Remedy5");
    }  
    
    // сведения о долях в праве собственности на объекты имущсетва 
    public function Remedy63Request($query = [])
    {
        return $this->InfoRequest("Remedy63");
    }  
    
    // сведения об объектах интеллектулаьной собственности
    public function Remedy75Request($query = [])
    {
        return $this->InfoRequest("Remedy75");
    } 
    
    // сведения о предприятиях и учреждениях
    public function Remedy1Request($query = [])
    {
        return $this->InfoRequest("Remedy1");
    }     
}
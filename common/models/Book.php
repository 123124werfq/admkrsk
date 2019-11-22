<?php

namespace common\models;

use Yii;


class CSOAPOperationStart{
    public $start;
    public $id;
}

class CSOAPClient {
    public $Name;
    public $Email;
    public $Operation_id;
    public $Station;
    public $AInfo;
    public $Date;
    public $Time;
}


/**
 * This is the model class for table "db_book".
 *
 * @property int $id_book
 * @property int $id_user
 * @property int $damask_number
 * @property string $office
 * @property string $operation
 * @property string $date
 * @property string $time
 * @property string $pin
 * @property string $state
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class Book extends \yii\db\ActiveRecord
{
    private $endpoint = ['http://192.168.38.113/preorder_service/wsdlv2', 'http://192.168.70.9/preorder_service/wsdlv2'];
    private $operations;
    private $service = false;
    private $offices;
    private $officeId;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'damask_number', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_user', 'damask_number', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['office', 'operation', 'date', 'time', 'pin', 'state'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_book' => 'Id Book',
            'id_user' => 'Id User',
            'damask_number' => 'Damask Number',
            'office' => 'Office',
            'operation' => 'Operation',
            'date' => 'Date',
            'time' => 'Time',
            'pin' => 'Pin',
            'state' => 'State',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }


    public function connect($num){
        if(!isset($this->endpoint[$num]))
            return false;

        $this->service = new \SoapClient($this->endpoint[$num]);
        $this->offices = $this->service->getOffices();
        $this->officeId = $this->offices[0]->ID;
        return $this->offices;
    }

    public function operations($offId = -1)
    {
        if(!$this->service)
            return false;

        if($offId == -1)
            $this->operations = $this->service->getOperations();
        else
        {
            $this->operations = $this->service->getOperationsForOffice($offId);
            $this->officeId = $offId;
        }

        return $this->operations;
    }

    public function getTree($num)
    {
        $of = $this->connect($num);

        $tree = $this->service->GetTree($of[0]->ID);

        return $tree;
    }

    public function dateAvailable($operation_id)
    {
        $of = $this->service->getOfficesForOperation($operation_id);

        if(!isset($of[0]))
            return false;

        $aliases = [$operation_id];

        $dates = $this->service->GetFreeDates($of[0]->ID, $aliases, 1);
        //var_dump($dates); die();
        return $dates;

    }

    public function freeIntervals($operation_id, $dateText)
    {
        $of = $this->service->getOfficesForOperation($operation_id);
        var_dump($of);
        if(!isset($of[0]))
            return false;

        $ds = explode('.', $dateText);

        $intervals = $this->service->getIntervals($of[0]->ID, [$operation_id], $ds[2]."-".$ds[1]."-".$ds[0] , 1);
        var_dump($intervals); die();
        return $intervals;
    }

    public function reserveTime($operation_id, $date, $time)
    {
        $alias = new CSOAPOperationStart;
        $alias->start = $time;
        $alias->id = $operation_id;

        $res = $this->service->reserveTime($this->officeId, [$alias], $date, 1, "ru"); // 1341

        var_dump($res);

        if(isset($res->reserveCode) && !empty($res->reserveCode))
        {
            $user = User::findOne(Yii::$app->user->id);
            $esiauser = $user->getEsiainfo()->one();

            $phone = str_replace("+7", "8", $esiauser->mobile);
            $phone = str_replace("(", "", $phone);
            $phone = str_replace(")", "", $phone);
            $phone = str_replace(" ", "", $phone);


            $client = new CSOAPClient;
            $client->Name = $esiauser->first_name . ' ' . $esiauser->middle_name. ' ' . $esiauser->last_name;
            //$client->Email = $user->email;
            //$client->Operation_id = $operation_id;
            $client->AInfo = json_encode(['phone' => $phone]);
            //$client->Date = str_replace("-", ".", $date);
            $client->Date = $date;
            $client->time = $time;

            $ares = $this->service->activateTime($this->officeId, $client, 1);

            return($ares);
        }

        //$res = $this->service->reserveTime($this->officeId, (int)$operation_id, $date, $time, 1, "ru");
        return $res;
    }

}



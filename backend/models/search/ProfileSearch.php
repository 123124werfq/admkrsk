<?php

namespace backend\models\search;

use Yii;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use common\models\HrProfile;

/**
 * TagSearch represents the model behind the search form of `common\models\Tag`.
 */
class ProfileSearch extends HrProfile
{
    public $surname;
    public $plist;
    public $status;
    public $usr;
    public $preselected;
    public $secondary_status;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_profile'], 'integer'],
            [['preselected'], 'integer'],
            [['surname'], 'string'],
            [['plist'], 'string'],
            [['status'], 'integer'],
            [['secondary_status'], 'integer'],
            [['usr'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     * @throws InvalidConfigException
     */
    public function search($params)
    {


        /*
        $query = HrProfile::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['updated_at'=>SORT_DESC]],
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? 10
            ],
        ]);
        */

        $sql = "select hp.*, surname, name, patronic, plist from hr_profile hp
                    left join
                    (select id_record, value as surname from db_collection_value  where id_column = 1061) tsurname on tsurname.id_record = hp.id_record
                    left join
                    (select id_record, value as name from db_collection_value  where id_column = 1062) tname on tname.id_record = hp.id_record
                    left join
                    (select id_record, value as patronic from db_collection_value where id_column = 1063) tpatronic on tpatronic.id_record = hp.id_record
                    left join 
                    (select id_profile, string_agg(value, '<br>') plist from hr_profile_positions hpp 
                        left join db_collection_value dcv on hpp.id_record_position = dcv.id_record
                        group by id_profile) tp on tp.id_profile = hp.id_profile
                    where 1=1";
                           
        $this->load($params);

        if(isset($this->preselected) && ($this->preselected === 0 || $this->preselected == 1))
        {
            $sql .= " and preselected=".$this->preselected;
        }


        if(!empty($this->surname))
            $sql .= " and lower(surname) like('%".mb_strtolower(addslashes($this->surname), "UTF8")."%')";

        if(!empty($this->usr))
            $sql .= ($this->usr==1)?" and id_user is not null":" and id_user is null";

        if(!empty($this->plist))
        {
            $tsql = "select distinct value as val from hr_profile_positions hpp 
            left join db_collection_value dcv on hpp.id_record_position = dcv.id_record
            order by value";            
            $positionsRaw = Yii::$app->db->createCommand($tsql)->queryAll();
            $positions = [];
    
                for ($i=0; $i < count($positionsRaw); $i++) { 
                    $positions[$i+1] = $positionsRaw[$i]['val'];
                }

            $sql .= " and plist like('%".mb_strtolower(addslashes($positions[$this->plist]), "UTF8")."%')";
        }

        if(!empty($this->status) || $this->status=='0')
        {
            if($this->status == HrProfile::STATE_ACTIVE)
                $sql .= " and (state='".(int)$this->status."' or state is null)";
            else
                $sql .= " and state='".(int)$this->status."'";
        }
        else
            $sql .= " and (state<>'".HrProfile::STATE_ARCHIVED."' or state is null)";

        if(!empty($this->secondary_status) || $this->secondary_status=='0')
        {
            if($this->secondary_status == HrProfile::STATE_ACTIVE)
                $sql .= " and (secondary_status='".(int)$this->secondary_status."' or secondary_status is null)";
            else
                $sql .= " and secondary_status='".(int)$this->secondary_status."'";
        }
        else
            $sql .= " and (secondary_status<>'".HrProfile::STATE_ARCHIVED."' or secondary_status is null)";            
        

//echo $sql; die();
        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($sql) t1")->queryScalar();

        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [],
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder'=> ['id_profile'=>SORT_DESC, 'preselected' => SORT_DESC],
                'attributes' => [
                    'id_profile',                    
                    'updated_at',
                    'created_at' => [
                        'asc' => ['created_at' => SORT_ASC],
                        'desc' => ['created_at' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'preselected' => [
                        'asc' => ['preselected' => SORT_ASC],
                        'desc' => ['preselected' => SORT_DESC],
                        'default' => SORT_DESC
                    ],                    
                    'status' => [
                        'asc' => ['state' => SORT_ASC],
                        'desc' => ['state' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'secondary_status' => [
                        'asc' => ['secondary_status' => SORT_ASC],
                        'desc' => ['stsecondary_statusate' => SORT_DESC],
                        'default' => SORT_ASC
                    ],                    
                    'surname' => [
                        'asc' => ['state' => SORT_ASC],
                        'desc' => ['state' => SORT_DESC],
                        'default' => SORT_ASC
                    ],                    
                ],
            ],
        ]);                     


        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions

        return $dataProvider;
    }
}

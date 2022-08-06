<?php

namespace backend\models\search;

use Yii;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Integration;

use yii\data\SqlDataProvider;
use common\models\Collection;
use yii\web\BadRequestHttpException;

class ServiceStatisticSearch extends Integration
{
    public $reestr_number;
    public $resstate;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reestr_number'], 'string'],
            [['resstate'], 'integer'],
//            [['year'], 'default', 'value' => null],
//            [['model'], 'safe'],
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
     */
    public function search($params)
    {
        //var_dump($params); die();
        $sql = "select *, st.reestr_number, st.name as target_name from(
                    select sa.id_appeal, sa.id_service, sa.id_target, sa.number_internal, sa.number_system , MAX(sas.\"date\") as resdate, max(sas.state) as resstate, string_agg(sas.state,'→' order by sas.id_state) as state_history from service_appeal sa 
                    left join service_appeal_state sas on sas.id_appeal = sa.id_appeal 
                    group by sa.id_appeal
                    ) t1 
                    left join service_target st on st.id_target = CAST(coalesce(t1.id_target, '0') AS integer)
                    ";
        
        $cond = [];

        if(isset($params['ServiceStatisticSearch']['reestr_number']) && !empty($params['ServiceStatisticSearch']['reestr_number']))
            $cond[] = "reestr_number='{$params['ServiceStatisticSearch']['reestr_number']}'";

        if(isset($params['ServiceStatisticSearch']['resstate']) && !empty($params['ServiceStatisticSearch']['resstate']))
            $cond[] = "resstate='{$params['ServiceStatisticSearch']['resstate']}'";

        if(isset($params['createTimeRange']) && !empty($params['createTimeRange']))
        {
            $ranges = explode(" - ", $params['createTimeRange']);
            $cond[] = "resdate>".strtotime($ranges[0]);
            $cond[] = "resdate<".strtotime($ranges[1]);
        }

        if(count($cond))
            $sql .= " where " . implode(" and ", $cond);

        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($sql) t1")->queryScalar();
    
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [],
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder'=> ['id_appeal'=>SORT_DESC],
                'attributes' => [
                    'id_appeal',
                    'id_service',
                    'id_target',
                    'number_internal',
                    'number_system',
                    'resdate' => [
                        'asc' => ['resdate' => SORT_ASC],
                        'desc' => ['resdate' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'resstate' => [
                        'asc' => ['state' => SORT_ASC],
                        'desc' => ['state' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'reestr_number' => [
                        'asc' => ['reestr_number' => SORT_ASC],
                        'desc' => ['reestr_number' => SORT_DESC],
                    ],
                    
                ],
            ],
        ]);        

        
        $this->load($params);
        

        if (!$this->validate()) {
            return $dataProvider;
        }
        

        // grid filtering conditions
        /*
        $query->andFilterWhere([
            //'contestinfo' => ,
        ]);
        */

        return $dataProvider;
    }
}

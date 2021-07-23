<?php

namespace backend\models\search;

use Yii;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use common\models\CstProfile;
use common\models\Collection;
use yii\web\BadRequestHttpException;

/**
 * TagSearch represents the model behind the search form of `common\models\Tag`.
 */
class CprofileSearch extends CstProfile
{
    public $id_contest;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_contest'], 'integer']
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
        $query = CstProfile::find();

        // var_dump($query); die();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['updated_at'=>SORT_DESC]],
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? 200
            ],
        ]);
        */

        
        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection)
            throw new BadRequestHttpException();

        $contests = $contestCollection->getDataQuery()->getArray(true);
        
        if(isset($contests[$this->id_contest]))
            $sql = "SELECT cp.*, id_record FROM cst_profile cp 
                LEFT JOIN form_form ff ON cp.id_record_contest = ff.id_collection
                left join db_collection_record cr on cr.id_record = cp.id_record_anketa 
                WHERE ff.alias = '{$contests[$this->id_contest]['participant_form']}' and cr.created_by is not null and cr.deleted_at is null";
        else
        {
            //$sql = "SELECT * FROM cst_profile cp";
            $sql = "SELECT cp.*, id_record FROM cst_profile cp 
                left join db_collection_record cr on cr.id_record = cp.id_record_anketa 
                WHERE cr.created_by is not null and cr.deleted_at is null";
        }

        $count = Yii::$app->db->createCommand("SELECT COUNT(*) FROM ($sql) t1")->queryScalar();
    
        $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => [],
            'totalCount' => $count,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder'=> ['id_profile'=>SORT_DESC],
                'attributes' => [
                    'id_profile',
                    'updated_at',
                    'created_at' => [
                        'asc' => ['created_at' => SORT_ASC],
                        'desc' => ['created_at' => SORT_DESC],
                        'default' => SORT_ASC
                    ],
                    'state' => [
                        'asc' => ['state' => SORT_ASC],
                        'desc' => ['state' => SORT_DESC],
                        'default' => SORT_ASC
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

<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\House;

/**
 * HouseSearch represents the model behind the search form of `common\models\House`.
 */
class HouseSearch extends House
{
    public $housename;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['buildnum', 'enddate', 'housenum', 'ifnsfl', 'ifnsul', 'okato', 'oktmo', 'postalcode', 'startdate', 'strucnum', 'terrifnsfl', 'terrifnsul', 'updatedate', 'cadnum', 'aoguid', 'houseguid', 'houseid', 'normdoc', 'housename'], 'safe'],
            [['eststatus', 'statstatus', 'strstatus', 'counter', 'divtype'], 'integer'],
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
        $query = House::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'housenum' => SORT_ASC,
                    'buildnum' => SORT_ASC,
                    'strucnum' => SORT_ASC,
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
        $query->andFilterWhere([
            'enddate' => $this->enddate,
            'startdate' => $this->startdate,
            'updatedate' => $this->updatedate,
            'eststatus' => $this->eststatus,
            'statstatus' => $this->statstatus,
            'strstatus' => $this->strstatus,
            'counter' => $this->counter,
            'divtype' => $this->divtype,
            'aoguid' => $this->aoguid,
            'houseguid' => $this->houseguid,
        ]);

        $query->andFilterWhere(['ilike', 'buildnum', $this->buildnum])
            ->andFilterWhere(['ilike', 'housenum', $this->housenum])
            ->andFilterWhere(['ilike', 'ifnsfl', $this->ifnsfl])
            ->andFilterWhere(['ilike', 'ifnsul', $this->ifnsul])
            ->andFilterWhere(['ilike', 'okato', $this->okato])
            ->andFilterWhere(['ilike', 'oktmo', $this->oktmo])
            ->andFilterWhere(['ilike', 'postalcode', $this->postalcode])
            ->andFilterWhere(['ilike', 'strucnum', $this->strucnum])
            ->andFilterWhere(['ilike', 'terrifnsfl', $this->terrifnsfl])
            ->andFilterWhere(['ilike', 'terrifnsul', $this->terrifnsul])
            ->andFilterWhere(['ilike', 'cadnum', $this->cadnum])
            ->andFilterWhere(['ilike', 'houseid', $this->houseid])
            ->andFilterWhere(['ilike', 'normdoc', $this->normdoc]);

        if ($this->housename) {
            $query->andFilterWhere([
                'or',
                ['ilike', 'housenum', $this->housename],
                ['ilike', 'buildnum', $this->housename],
                ['ilike', 'strucnum', $this->housename],
            ]);
        }

        return $dataProvider;
    }
}

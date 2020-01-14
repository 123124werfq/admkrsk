<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Street;

/**
 * StreetSearch represents the model behind the search form of `common\models\Street`.
 */
class StreetSearch extends Street
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_street'], 'integer'],
            [['is_updatable'], 'boolean'],
            [['aoguid', 'name'], 'safe'],
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
        $query = Street::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_street' => $this->id_street,
            'is_updatable' => $this->is_updatable,
        ]);

        $query->andFilterWhere(['ilike', 'aoguid', $this->aoguid])
            ->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
}

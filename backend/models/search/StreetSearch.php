<?php

namespace backend\models\search;

use common\models\District;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Street;

/**
 * StreetSearch represents the model behind the search form of `common\models\Street`.
 */
class StreetSearch extends Street
{
    public $id_district;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_street', 'id_city', 'id_district'], 'integer'],
            [['is_updatable', 'is_active'], 'boolean'],
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
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params)
    {
        $query = Street::find()
            ->joinWith('districts', false);

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
            Street::tableName() . '.id_street' => $this->id_street,
            Street::tableName() . '.id_city' => $this->id_city,
            District::tableName() . '.id_district' => $this->id_district,
            Street::tableName() . '.is_active' => $this->is_active,
            Street::tableName() . '.is_updatable' => $this->is_updatable,
        ]);

        $query->andFilterWhere(['ilike', Street::tableName() . '.aoguid', $this->aoguid])
            ->andFilterWhere(['ilike', Street::tableName() . '.name', $this->name]);

        return $dataProvider;
    }
}

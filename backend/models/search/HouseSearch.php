<?php

namespace backend\models\search;

use common\models\Place;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\House;
use yii\db\Expression;

/**
 * HouseSearch represents the model behind the search form of `common\models\House`.
 */
class HouseSearch extends House
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_house'], 'integer'],
            [['is_updatable', 'is_active'], 'boolean'],
            [['name', 'fullname'], 'safe'],
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

        $query->select([
            '*',
            'placeCount' => Place::find()
                ->select(['count' => new Expression('COUNT(' . Place::tableName() . '.id_house)')])
                ->where([
                    'id_house' => new Expression(House::tableName() . '.id_house'),
                ]),
        ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['placeCount'] = [
            'asc' => new Expression('[[placeCount]] ASC'),
            'desc' => new Expression('[[placeCount]] DESC'),
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_house' => $this->id_house,
            'is_active' => $this->is_active,
            'is_updatable' => $this->is_updatable,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'fullname', str_replace([' ',',','.'], '%', '%' . $this->fullname . '%'), false]);

        return $dataProvider;
    }
}

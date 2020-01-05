<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Notify;

/**
 * NotifySearch represents the model behind the search form of `common\models\Notify`.
 */
class NotifySearch extends Notify
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'class', 'main_notify', 'repeat_notify', 'created_at', 'updated_at'], 'integer'],
            [['message'], 'safe'],
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
        $query = Notify::find();

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
            'id' => $this->id,
            'class' => $this->class,
            'main_notify' => $this->main_notify,
            'repeat_notify' => $this->repeat_notify,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['ilike', 'message', $this->message]);

        return $dataProvider;
    }
}

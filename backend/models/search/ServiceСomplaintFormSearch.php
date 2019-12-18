<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ServiceСomplaintForm;

/**
 * ServiceСomplaintFormSearch represents the model behind the search form of `common\models\ServiceСomplaintForm`.
 */
class ServiceСomplaintFormSearch extends ServiceСomplaintForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_appeal', 'id_form', 'id_record_firm', 'id_record_category', 'id_service'], 'integer'],
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
        $query = ServiceСomplaintForm::find();

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
            'id_appeal' => $this->id_appeal,
            'id_form' => $this->id_form,
            'id_record_firm' => $this->id_record_firm,
            'id_record_category' => $this->id_record_category,
            'id_service' => $this->id_service,
        ]);

        return $dataProvider;
    }
}

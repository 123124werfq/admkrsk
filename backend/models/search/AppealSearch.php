<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AppealRequest;

/**
 * ServiceAppealSearch represents the model behind the search form of `common\models\ServiceAppeal`.
 */
class AppealSearch extends AppealRequest
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_request', 'id_user', 'is_anonimus', 'id_service', 'date', 'archive', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id_record', 'id_collection', 'number_system', 'number_common'], 'integer'],
            [['state', 'data', 'number_internal', 'id_target'], 'safe'],
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
        $query = AppealRequest::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id_appeal'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_request' => $this->id_appeal,
            'id_user' => $this->id_user,
            'is_anonimus' => $this->is_anonimus,
            'id_service' => $this->id_service,
            'date' => $this->date,
            'archive' => $this->archive,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
            'id_record' => $this->id_record,
            'id_collection' => $this->id_collection,
            'number_internal' => $this->number_internal,
            'number_system' => $this->number_system,
            'number_common' => $this->number_common,
        ]);

        $query->andFilterWhere(['ilike', 'state', $this->state])
            ->andFilterWhere(['ilike', 'data', $this->data])
            ->andFilterWhere(['ilike', 'number_internal', $this->number_internal])
            ->andFilterWhere(['ilike', 'id_target', $this->id_target]);

        return $dataProvider;
    }
}

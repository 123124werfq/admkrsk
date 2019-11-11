<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Institution;

/**
 * InstitutionSearch represents the model behind the search form of `common\models\Institution`.
 */
class InstitutionSearch extends Institution
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_institution', 'description', 'comment', 'bus_id', 'fullname', 'shortname', 'type', 'okved_code', 'okved_name', 'ppo', 'ppo_oktmo_name', 'ppo_oktmo_code', 'ppo_okato_name', 'ppo_okato_code', 'okpo', 'okopf_name', 'okopf_code', 'okfs_name', 'okfs_code', 'oktmo_name', 'oktmo_code', 'okato_name', 'okato_code', 'address_zip', 'address_subject', 'address_region', 'address_locality', 'address_street', 'address_building', 'address_latitude', 'address_longitude', 'vgu_name', 'vgu_code', 'inn', 'kpp', 'ogrn', 'phone', 'email', 'website', 'manager_position', 'manager_firstname', 'manager_middlename', 'manager_lastname', 'version'], 'safe'],
            [['status', 'last_update', 'modified_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['is_updating'], 'boolean'],
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
        $query = Institution::find();

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
            'status' => $this->status,
            'is_updating' => $this->is_updating,
            'last_update' => $this->last_update,
            'modified_at' => $this->modified_at,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'id_institution', $this->id_institution])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'comment', $this->comment])
            ->andFilterWhere(['ilike', 'bus_id', $this->bus_id])
            ->andFilterWhere(['ilike', 'fullname', $this->fullname])
            ->andFilterWhere(['ilike', 'shortname', $this->shortname])
            ->andFilterWhere(['ilike', 'type', $this->type])
            ->andFilterWhere(['ilike', 'okved_code', $this->okved_code])
            ->andFilterWhere(['ilike', 'okved_name', $this->okved_name])
            ->andFilterWhere(['ilike', 'ppo', $this->ppo])
            ->andFilterWhere(['ilike', 'ppo_oktmo_name', $this->ppo_oktmo_name])
            ->andFilterWhere(['ilike', 'ppo_oktmo_code', $this->ppo_oktmo_code])
            ->andFilterWhere(['ilike', 'ppo_okato_name', $this->ppo_okato_name])
            ->andFilterWhere(['ilike', 'ppo_okato_code', $this->ppo_okato_code])
            ->andFilterWhere(['ilike', 'okpo', $this->okpo])
            ->andFilterWhere(['ilike', 'okopf_name', $this->okopf_name])
            ->andFilterWhere(['ilike', 'okopf_code', $this->okopf_code])
            ->andFilterWhere(['ilike', 'okfs_name', $this->okfs_name])
            ->andFilterWhere(['ilike', 'okfs_code', $this->okfs_code])
            ->andFilterWhere(['ilike', 'oktmo_name', $this->oktmo_name])
            ->andFilterWhere(['ilike', 'oktmo_code', $this->oktmo_code])
            ->andFilterWhere(['ilike', 'okato_name', $this->okato_name])
            ->andFilterWhere(['ilike', 'okato_code', $this->okato_code])
            ->andFilterWhere(['ilike', 'address_zip', $this->address_zip])
            ->andFilterWhere(['ilike', 'address_subject', $this->address_subject])
            ->andFilterWhere(['ilike', 'address_region', $this->address_region])
            ->andFilterWhere(['ilike', 'address_locality', $this->address_locality])
            ->andFilterWhere(['ilike', 'address_street', $this->address_street])
            ->andFilterWhere(['ilike', 'address_building', $this->address_building])
            ->andFilterWhere(['ilike', 'address_latitude', $this->address_latitude])
            ->andFilterWhere(['ilike', 'address_longitude', $this->address_longitude])
            ->andFilterWhere(['ilike', 'vgu_name', $this->vgu_name])
            ->andFilterWhere(['ilike', 'vgu_code', $this->vgu_code])
            ->andFilterWhere(['ilike', 'inn', $this->inn])
            ->andFilterWhere(['ilike', 'kpp', $this->kpp])
            ->andFilterWhere(['ilike', 'ogrn', $this->ogrn])
            ->andFilterWhere(['ilike', 'phone', $this->phone])
            ->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'website', $this->website])
            ->andFilterWhere(['ilike', 'manager_position', $this->manager_position])
            ->andFilterWhere(['ilike', 'manager_firstname', $this->manager_firstname])
            ->andFilterWhere(['ilike', 'manager_middlename', $this->manager_middlename])
            ->andFilterWhere(['ilike', 'manager_lastname', $this->manager_lastname])
            ->andFilterWhere(['ilike', 'version', $this->version]);

        return $dataProvider;
    }
}

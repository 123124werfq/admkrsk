<?php

namespace backend\models\search;

use common\models\AuthEntity;
use common\traits\ActiveRangeValidateTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Service;

/**
 * ServiceSearch represents the model behind the search form of `common\models\Service`.
 */
class ServiceSearch extends Service
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_service', 'id_rub', 'client_type', 'old', 'online', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['reestr_number', 'fullname', 'name', 'keywords', 'addresses', 'result', 'client_category', 'duration', 'documents', 'price', 'appeal', 'legal_grounds', 'regulations', 'regulations_link', 'duration_order', 'availability', 'procedure_information', 'max_duration_queue'], 'safe'],
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
        if (Yii::$app->request->get('archive')) {
            $query = Service::findDeleted();
        } else {
            $query = Service::find();
        }

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.service')) {
            $query->andWhere(['id_service' => AuthEntity::getEntityIds(Service::class)]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? 10
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
            'id_service' => $this->id_service,
            'id_rub' => $this->id_rub,
            'client_type' => $this->client_type,
            'old' => $this->old,
            'online' => $this->online,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'reestr_number', $this->reestr_number])
            ->andFilterWhere(['ilike', 'fullname', $this->fullname])
            ->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'keywords', $this->keywords])
            ->andFilterWhere(['ilike', 'addresses', $this->addresses])
            ->andFilterWhere(['ilike', 'result', $this->result])
            ->andFilterWhere(['ilike', 'client_category', $this->client_category])
            ->andFilterWhere(['ilike', 'duration', $this->duration])
            ->andFilterWhere(['ilike', 'documents', $this->documents])
            ->andFilterWhere(['ilike', 'price', $this->price])
            ->andFilterWhere(['ilike', 'appeal', $this->appeal])
            ->andFilterWhere(['ilike', 'legal_grounds', $this->legal_grounds])
            ->andFilterWhere(['ilike', 'regulations', $this->regulations])
            ->andFilterWhere(['ilike', 'regulations_link', $this->regulations_link])
            ->andFilterWhere(['ilike', 'duration_order', $this->duration_order])
            ->andFilterWhere(['ilike', 'availability', $this->availability])
            ->andFilterWhere(['ilike', 'procedure_information', $this->procedure_information])
            ->andFilterWhere(['ilike', 'max_duration_queue', $this->max_duration_queue]);

        return $dataProvider;
    }
}

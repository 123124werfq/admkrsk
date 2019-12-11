<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ServiceTarget;
use Yii;
/**
 * ServiceTargetSearch represents the model behind the search form of `common\models\ServiceTarget`.
 */
class ServiceTargetSearch extends ServiceTarget
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_target', 'id_service', 'id_form', 'modified_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id_media_template'], 'integer'],
            [['name', 'reestr_number', 'target', 'target_code', 'service_code', 'obj_name', 'place', 'state'], 'safe'],
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
        if (Yii::$app->request->get('archive')) {
            $query = ServiceTarget::findDeleted();
        } else {
            $query = ServiceTarget::find();
        }

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
            'id_target' => $this->id_target,
            'id_service' => $this->id_service,
            'id_form' => $this->id_form,
            'modified_at' => $this->modified_at,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
            'id_media_template' => $this->id_media_template,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'reestr_number', $this->reestr_number])
            ->andFilterWhere(['ilike', 'target', $this->target])
            ->andFilterWhere(['ilike', 'target_code', $this->target_code])
            ->andFilterWhere(['ilike', 'service_code', $this->service_code])
            ->andFilterWhere(['ilike', 'obj_name', $this->obj_name])
            ->andFilterWhere(['ilike', 'place', $this->place])
            ->andFilterWhere(['ilike', 'state', $this->state]);

        return $dataProvider;
    }
}

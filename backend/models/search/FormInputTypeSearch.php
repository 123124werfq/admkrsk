<?php

namespace backend\models\search;

use common\models\AuthEntity;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FormInputType;

/**
 * FormInputTypeSearch represents the model behind the search form of `common\models\FormInputType`.
 */
class FormInputTypeSearch extends FormInputType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_type', 'id_collection', 'type', 'esia', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name', 'regexp', 'options', 'values'], 'safe'],
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
        $query = FormInputType::find();

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.formInputType')) {
            $query->andWhere(['id_type' => AuthEntity::getEntityIds(FormInputType::class)]);
        }

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
            'id_type' => $this->id_type,
            'id_collection' => $this->id_collection,
            'type' => $this->type,
            'esia' => $this->esia,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'regexp', $this->regexp])
            ->andFilterWhere(['ilike', 'options', $this->options])
            ->andFilterWhere(['ilike', 'values', $this->values]);

        return $dataProvider;
    }
}

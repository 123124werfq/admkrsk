<?php

namespace backend\models\search;

use common\models\AuthEntity;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Collection;

/**
 * CollectionSearch represents the model behind the search form of `common\models\Collection`.
 */
class CollectionSearch extends Collection
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_collection', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name', 'alias', 'is_dictionary'], 'safe'],
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
        $query = Collection::find();

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.collection')) {
            $query->andWhere(['id_collection' => AuthEntity::getEntityIds(Collection::class)]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'defaultOrder' => [
                'date' => SORT_ASC
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_collection' => $this->id_collection,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name])
            ->andFilterWhere(['ilike', 'alias', $this->alias])
            ->andFilterWhere(['ilike', 'is_dictionary', $this->is_dictionary]);

        return $dataProvider;
    }
}

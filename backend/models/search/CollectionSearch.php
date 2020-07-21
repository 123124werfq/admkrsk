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
            [['id_collection', 'id_box', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
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
        if (Yii::$app->request->get('archive'))
            $query = Collection::findDeleted();
        else
            $query = Collection::find();

        $query->joinWith('form');
        $query->andWhere(['is_template'=>0]);

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.collection')) {
            $query->andFilterWhere(['db_collection.id_collection' => AuthEntity::getEntityIds(Collection::class)]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id_collection'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'db_collection.id_collection' => $this->id_collection,
            'db_collection.created_at' => $this->created_at,
            'db_collection.created_by' => $this->created_by,
            'db_collection.updated_at' => $this->updated_at,
            'db_collection.updated_by' => $this->updated_by,
            'db_collection.deleted_at' => $this->deleted_at,
            'db_collection.deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'db_collection.name', $this->name])
            ->andFilterWhere(['ilike', 'db_collection.alias', $this->alias])
            ->andFilterWhere(['ilike', 'is_dictionary', $this->is_dictionary]);

        return $dataProvider;
    }
}

<?php

namespace frontend\modules\api\models\search;

use frontend\modules\api\models\CollectionRecord;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\api\models\Collection;
use yii\helpers\ArrayHelper;

/**
 * CollectionSearch represents the model behind the search form of `frontend\modules\api\models\Collection`.
 */
class CollectionSearch extends Collection
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_collection', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id_parent_collection', 'id_form', 'label', 'id_group', 'system'], 'integer'],
            [['name', 'alias', 'filter', 'options', 'template', 'template_element'], 'safe'],
            [['is_dictionary'], 'boolean'],
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
        $query = CollectionRecord::find()
            ->alias('r')
            ->leftJoin(Collection::tableName() . ' c', 'c.id_collection = r.id_collection');

        // add conditions that should always apply here
        $query->andWhere(['c.id_collection' => $this->id_collection]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        return $dataProvider;
    }
}

<?php

namespace backend\models\search;

use common\models\AuthEntity;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Opendata;

/**
 * OpendataSearch represents the model behind the search form of `common\models\Opendata`.
 */
class OpendataSearch extends Opendata
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_opendata', 'id_collection', 'id_user', 'period', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['identifier', 'title', 'description', 'owner', 'keywords', 'columns'], 'safe'],
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
            $query = Opendata::findDeleted();
        } else {
            $query = Opendata::find();
        }

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.opendata')) {
            $query->andFilterWhere(['id_opendata' => AuthEntity::getEntityIds(Opendata::class)]);
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
            'id_opendata' => $this->id_opendata,
            'id_collection' => $this->id_collection,
            'id_user' => $this->id_user,
            'period' => $this->period,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'identifier', $this->identifier])
            ->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'owner', $this->owner])
            ->andFilterWhere(['ilike', 'keywords', $this->keywords])
            ->andFilterWhere(['ilike', 'columns', $this->columns]);

        return $dataProvider;
    }
}

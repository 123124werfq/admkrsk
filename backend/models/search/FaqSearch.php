<?php

namespace backend\models\search;

use common\models\AuthEntity;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Faq;

/**
 * FaqSearch represents the model behind the search form of `common\models\Faq`.
 */
class FaqSearch extends Faq
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_faq', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['status', 'question', 'answer'], 'safe'],
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
        $query = Faq::find();

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.faq')) {
            $query->andWhere(['id_faq' => AuthEntity::getEntityIds(Faq::class)]);
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
            'id_faq' => $this->id_faq,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'status', $this->status])
            ->andFilterWhere(['ilike', 'question', $this->question])
            ->andFilterWhere(['ilike', 'answer', $this->answer]);

        return $dataProvider;
    }
}

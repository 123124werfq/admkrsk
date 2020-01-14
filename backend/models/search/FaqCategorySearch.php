<?php

namespace backend\models\search;

use common\models\AuthEntity;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FaqCategory;

/**
 * FaqCategorySearch represents the model behind the search form of `common\models\FaqCategory`.
 */
class FaqCategorySearch extends FaqCategory
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_faq_category', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['title'], 'safe'],
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
            $query = FaqCategory::findDeleted();
        } else {
            $query = FaqCategory::find();
        }

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.faqCategory')) {
            $query->andFilterWhere(['id_faq_category' => AuthEntity::getEntityIds(FaqCategory::class)]);
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
            'id_faq_category' => $this->id_faq_category,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title]);

        return $dataProvider;
    }
}

<?php

namespace frontend\modules\api\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\modules\api\models\News;

/**
 * NewsSearch represents the model behind the search form of `frontend\modules\api\models\News`.
 */
class NewsSearch extends News
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_news', 'id_page', 'id_category', 'id_rub', 'id_media', 'date_publish', 'date_unpublish', 'state', 'main', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id_user_contact', 'id_user', 'id_record_contact', 'highlight'], 'integer'],
            [['title', 'description', 'content', 'url'], 'safe'],
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
        $query = News::find();

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
            'id_news' => $this->id_news,
            'id_page' => $this->id_page,
            'id_category' => $this->id_category,
            'id_rub' => $this->id_rub,
            'id_media' => $this->id_media,
            'date_publish' => $this->date_publish,
            'date_unpublish' => $this->date_unpublish,
            'state' => $this->state,
            'main' => $this->main,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
            'id_user_contact' => $this->id_user_contact,
            'id_user' => $this->id_user,
            'id_record_contact' => $this->id_record_contact,
            'highlight' => $this->highlight,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'content', $this->content])
            ->andFilterWhere(['ilike', 'url', $this->url]);

        return $dataProvider;
    }
}

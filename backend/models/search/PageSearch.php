<?php

namespace backend\models\search;

use common\models\AuthEntity;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Page;

/**
 * PageSearch represents the model behind the search form of `common\models\Page`.
 */
class PageSearch extends Page
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_page', 'id_media', 'active', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by'], 'integer'],
            [['title', 'alias', 'content', 'seo_title', 'seo_description', 'seo_keywords'], 'safe'],
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
            $query = Page::findDeleted();
        } else {
            $query = Page::find();
        }

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.page')) {
            $query->andFilterWhere(['id_page' => AuthEntity::getEntityIds(Page::class)]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id_page' => SORT_DESC
                ]
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
            'id_page' => $this->id_page,
            'id_media' => $this->id_media,
            'active' => $this->active,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'alias', $this->alias])
            ->andFilterWhere(['ilike', 'content', $this->content])
            ->andFilterWhere(['ilike', 'seo_title', $this->seo_title])
            ->andFilterWhere(['ilike', 'seo_description', $this->seo_description])
            ->andFilterWhere(['ilike', 'seo_keywords', $this->seo_keywords]);

        return $dataProvider;
    }
}

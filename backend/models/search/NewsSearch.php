<?php

namespace backend\models\search;

use common\models\AuthEntity;
use common\traits\ActiveRangeValidateTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\News;

/**
 * NewsSearch represents the model behind the search form of `common\models\News`.
 */
class NewsSearch extends News
{
    use ActiveRangeValidateTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_news', 'id_page', 'id_category', 'id_rub', 'id_media', 'state', 'main', 'created_by', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['title', 'description', 'content','date_publish', 'date_unpublish', 'created_at', 'updated_at'], 'safe'],
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

    public function beforeValidate()
    {
        return true;
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
            $query = News::findDeleted();
        } else {
            $query = News::find();
        }

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.news')) {
            $query->andWhere(['id_news' => AuthEntity::getEntityIds(News::class)]);
        }

        $id_page = Yii::$app->request->get('id_page');

        if (!empty($id_page))
            $query->andWhere(['id_page' => $id_page]);

        if (!Yii::$app->request->get('sort'))
            $query->orderBy('id_news DESC');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => 'id_news DESC']
        ]);

        $this->load($params);

        $this->handleDateRange($this->date_publish, $query, 'date_publish');
        $this->handleDateRange($this->date_unpublish, $query, 'date_unpublish');
        $this->handleDateRange($this->created_at, $query, 'created_at');
        $this->handleDateRange($this->updated_at, $query, 'updated_at');

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
            'state' => $this->state,
            'main' => $this->main,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'content', $this->content]);

        return $dataProvider;
    }
}

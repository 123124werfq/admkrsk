<?php

namespace backend\models\search;

use common\models\AuthEntity;
use common\models\Statistic;
use common\traits\ActiveRangeValidateTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

use common\models\Page;

/**
 * PageSearch represents the model behind the search form of `common\models\Page`.
 */
class PageSearch extends Page
{
    use ActiveRangeValidateTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_page', 'id_media', 'active', 'created_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['title', 'alias', 'content', 'seo_title', 'seo_description', 'seo_keywords','created_at', 'updated_at'], 'safe'],
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
     * @throws NotFoundHttpException
     */
    public function search($params)
    {
        if (Yii::$app->request->get('archive')) {
            $query = Page::findDeleted();
        } else {
            $query = Page::find();
        }

        $query->andWhere('type <> '.Page::TYPE_LINK);

        $query->select([
            '*',
            'views' => Statistic::find()
                ->select('views')
                ->where([
                    'model_id' => new Expression(Page::tableName() . '.id_page'),
                    'model' => Page::class,
                    'year' => null,
                ]),
            'viewsYear' => Statistic::find()
                ->select('views')
                ->where([
                    'model_id' => new Expression(Page::tableName() . '.id_page'),
                    'model' => Page::class,
                    'year' => (int) Yii::$app->formatter->asDate(time(), 'Y'),
                ]),
        ]);

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.page')) {
            if (!empty($entityIds = AuthEntity::getEntityIds(Page::class))) {
                $query->andFilterWhere(['id_page' => $entityIds]);
            } else {
                $query->andWhere('0=1');
            }
        }

        $id_partition = Yii::$app->request->get('id_partition',null);

        if (!empty($id_partition))
        {
            $partition = Page::findOne($id_partition);

            if (empty($partition))
                throw new NotFoundHttpException('The requested page does not exist.');

            $query = $query->andWhere('lft > '.$partition->lft.' AND rgt <'.$partition->rgt);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id_page' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? 10
            ],
        ]);

        $dataProvider->sort->attributes['views'] = [
            'asc' => new Expression('[[views]] ASC NULLS FIRST'),
            'desc' => new Expression('[[views]] DESC NULLS LAST'),
        ];

        $dataProvider->sort->attributes['viewsYear'] = [
            'asc' => new Expression('[[viewsYear]] ASC NULLS FIRST'),
            'desc' => new Expression('[[viewsYear]] DESC NULLS LAST'),
        ];

        $this->load($params);

        $this->handleDateRange($this->created_at,$query,'created_at');
        $this->handleDateRange($this->updated_at,$query,'updated_at');

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
            'created_by' => $this->created_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['or',['ilike', 'alias', $this->alias],['ilike', 'partition_domain', $this->alias]]);
            ->andFilterWhere(['ilike', 'content', $this->content])
            ->andFilterWhere(['ilike', 'seo_title', $this->seo_title])
            ->andFilterWhere(['ilike', 'seo_description', $this->seo_description])
            ->andFilterWhere(['ilike', 'seo_keywords', $this->seo_keywords]);

        return $dataProvider;
    }
}

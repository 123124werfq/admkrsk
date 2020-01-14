<?php

namespace backend\models\search;

use common\models\AuthEntity;
use common\traits\ActiveRangeValidateTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Poll;

/**
 * PollSearch represents the model behind the search form of `common\modules\poll\models\Poll`.
 */
class PollSearch extends Poll
{
    use ActiveRangeValidateTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_poll', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['title', 'description', 'result'], 'safe'],
            [['is_anonymous', 'is_hidden'], 'boolean'],
            [['date_start','date_end'],'string']
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
            $query = Poll::findDeleted();
        } else {
            $query = Poll::find();
        }

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.poll')) {
            $query->andFilterWhere(['id_poll' => AuthEntity::getEntityIds(Poll::class)]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? 10
            ],
        ]);

        $this->load($params);

        $this->handleDateRange($this->date_start,$query,'date_start');
        $this->handleDateRange($this->date_end,$query,'date_end');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_poll' => $this->id_poll,
            'status' => $this->status,
            'is_anonymous' => $this->is_anonymous,
            'is_hidden' => $this->is_hidden,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'title', $this->title])
            ->andFilterWhere(['ilike', 'description', $this->description])
            ->andFilterWhere(['ilike', 'result', $this->result]);

        return $dataProvider;
    }
}

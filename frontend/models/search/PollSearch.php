<?php

namespace frontend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Poll;

/**
 * PollSearch represents the model behind the search form of `common\modules\poll\models\Poll`.
 */
class PollSearch extends Poll
{
    public $archive = false;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
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
        $query = Poll::find();

        // add conditions that should always apply here
        $query->where(['status' => Poll::STATUS_ACTIVE]);

        if ($this->archive) {
            $query->andWhere(['<', 'date_end', time()]);
        } else {
            $query->andWhere([
                'and',
                ['<', 'date_start', time()],
                ['>', 'date_end', time()],
            ]);
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

        return $dataProvider;
    }
}

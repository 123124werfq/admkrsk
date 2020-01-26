<?php

namespace backend\models\search;

use common\models\AuthEntity;
use common\traits\ActiveRangeValidateTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Form;

/**
 * FormSearch represents the model behind the search form of `common\models\Form`.
 */
class FormSearch extends Form
{
    use ActiveRangeValidateTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_form','id_collection','id_box', 'created_by', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name'], 'safe'],
            [['created_at', 'updated_at'], 'string'],
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
     * @return ActiveDataPrfovider
     * @throws InvalidConfigException
     */
    public function search($params)
    {
        if (Yii::$app->request->get('archive'))
            $query = Form::findDeleted();
        else
            $query = Form::find();

        if (Yii::$app->request->get('id'))
            $this->id_collection = Yii::$app->request->get('id');

        if (Yii::$app->request->get('is_template'))
            $query->andWhere(['is_template' => 1]);
        else
            $query->andWhere(['is_template' => 0]);

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.form')) {
            $query->andFilterWhere(['id_form' => AuthEntity::getEntityIds(Form::class)]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id_form' => SORT_DESC]
            ],
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

        $this->handleDateRange($this->created_at, $query, 'created_at');
        $this->handleDateRange($this->updated_at, $query, 'updated_at');

        // grid filtering conditions
        $query->andFilterWhere([
            'id_form' => $this->id_form,
            'id_collection' => $this->id_collection,
            'id_box' => $this->id_box,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
}

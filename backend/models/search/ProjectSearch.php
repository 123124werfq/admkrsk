<?php

namespace backend\models\search;

use common\models\AuthEntity;
use common\traits\ActiveRangeValidateTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Project;

/**
 * ProjectSearch represents the model behind the search form of `common\models\Project`.
 */
class ProjectSearch extends Project
{
    use ActiveRangeValidateTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_project', 'id_media', 'id_page', 'name', 'date_begin', 'date_end', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['url','type'], 'safe'],
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
            $query = Project::findDeleted();
        } else {
            $query = Project::find();
        }

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.project')) {
            if (!empty($entityIds = AuthEntity::getEntityIds(Project::class))) {
                $query->andFilterWhere(['id_project' => $entityIds]);
            } else {
                $query->andWhere('0=1');
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? 10
            ],
        ]);

        $this->load($params);

        $this->handleNumberRange($this->type,$query,'type');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_project' => $this->id_project,
            'id_media' => $this->id_media,
            'id_page' => $this->id_page,
            'name' => $this->name,
//            'type' => $this->type,
            'date_begin' => $this->date_begin,
            'date_end' => $this->date_end,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'url', $this->url]);

        return $dataProvider;
    }
}

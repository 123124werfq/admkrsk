<?php

namespace backend\models\search;

use common\models\AuthEntity;
use common\models\Gallery;
use common\traits\ActiveRangeValidateTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Gallery as GalleryModel;

/**
 * Gallery represents the model behind the search form of `common\models\Gallery`.
 */
class GallerySearch extends GalleryModel
{
    use ActiveRangeValidateTrait;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_gallery', 'id_page', 'created_by', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name', 'created_at', 'updated_at'], 'safe'],
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
            $query = GalleryModel::findDeleted();
        } else {
            $query = GalleryModel::find();
        }

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.gallery')) {
            $query->andFilterWhere(['id_gallery' => AuthEntity::getEntityIds(Gallery::class)]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? 10
            ],
        ]);

        $this->load($params);

        $this->handleNumberRange($this->created_at, $query, 'created_at');
        $this->handleNumberRange($this->updated_at, $query, 'updated_at');

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id_gallery' => $this->id_gallery,
            'id_page' => $this->id_page,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
}

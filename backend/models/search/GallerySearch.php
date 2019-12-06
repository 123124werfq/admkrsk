<?php

namespace backend\models\search;

use common\models\AuthEntity;
use common\models\Gallery;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Gallery as GalleryModel;

/**
 * Gallery represents the model behind the search form of `common\models\Gallery`.
 */
class GallerySearch extends GalleryModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_gallery', 'id_page', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name'], 'safe'],
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
            $query = GalleryModel::findDeleted();
        } else {
            $query = GalleryModel::find();
        }

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.gallery')) {
            $query->andWhere(['id_gallery' => AuthEntity::getEntityIds(Gallery::class)]);
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
            'id_gallery' => $this->id_gallery,
            'id_page' => $this->id_page,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['ilike', 'name', $this->name]);

        return $dataProvider;
    }
}

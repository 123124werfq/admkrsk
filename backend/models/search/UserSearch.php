<?php

namespace backend\models\search;

use common\models\AuthEntity;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class UserSearch extends User
{
    public $source;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'updated_at', 'id_esia_user', 'id_ad_user', 'source'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'verification_token', 'fullname'], 'safe'],
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
        $query = User::find()
            ->leftJoin('auth_ad_user', 'auth_ad_user.id_ad_user = "user".id_ad_user')
            ->leftJoin('auth_esia_user', 'auth_esia_user.id_esia_user = "user".id_esia_user');

        // add conditions that should always apply here
        if (!Yii::$app->user->can('admin.user')) {
            $query->andWhere(['id' => AuthEntity::getEntityIds(User::class)]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC]
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'id_esia_user' => $this->id_esia_user,
            'id_ad_user' => $this->id_ad_user,
        ]);

        /*
        $query->andFilterWhere(['ilike', 'username', $this->username])
            ->andFilterWhere(['ilike', 'auth_key', $this->auth_key])
            ->andFilterWhere(['ilike', 'password_hash', $this->password_hash])
            ->andFilterWhere(['ilike', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'verification_token', $this->verification_token])
            ->andFilterWhere(['ilike', 'auth_esia_user.fullname', $this->fullname])
            ->andFilterWhere(['ilike', 'auth_ad_user.displayname', $this->fullname])
            ->andFilterWhere(['ilike', 'auth_ad_user.name', $this->fullname]);
*/
//        var_dump($this->username); die();

        $query->andFilterWhere(['ilike', 'username', $this->username])
            ->andFilterWhere(['ilike', '"user".email', $this->email])
            ->orFilterWhere(['ilike', 'auth_esia_user.fullname', $this->username])
            ->orFilterWhere(['ilike', 'auth_ad_user.displayname', $this->username])
            ->orFilterWhere(['ilike', 'auth_ad_user.name', $this->username]);

        if ($this->source == 1) {
            $query->andWhere('"user".id_esia_user IS NOT NULL');
            $query->andWhere('"user".id_ad_user IS NULL');
        }
        if ($this->source == 2) {
            $query->andWhere('"user".id_ad_user IS NOT NULL');
            $query->andWhere('"user".id_esia_user IS NULL');
        }
        if ($this->source == 3) {
            $query->andWhere('"user".id_ad_user IS NOT NULL');
            $query->andWhere('"user".id_esia_user IS NOT NULL');
        }


        return $dataProvider;
    }
}

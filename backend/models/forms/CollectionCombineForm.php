<?php

namespace backend\models\forms;

use common\models\User;
use Yii;
use yii\base\Model;

class CollectionCombineForm extends Model
{
    public $id_collection;
    public $id_collection_column;

    public $id_collection_from;
    public $id_collection_from_column;

    public $alias

    public function rules()
    {
        return [
            [['id_collection','id_collection_column','id_collection_from','id_collection_from_column'], 'required'],
            [['id_collection','id_collection_column','id_collection_from','id_collection_from_column'], 'integer'],
            [['alias'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_collection' => 'Пользователь',
        ];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function assign()
    {
        if ($this->validate()) {
            /* @var yii\rbac\DbManager $auth */
            $auth = Yii::$app->authManager;

            $role = $auth->getRole($this->name);
            $user = User::findOne($this->id_user);

            if ($role && $user && !$user->can($role->name)) {
                $auth->assign($role, $user->id);
                $auth->invalidateCache();
                $this->id_user = null;
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function revoke()
    {
        if ($this->validate()) {
            /* @var yii\rbac\DbManager $auth */
            $auth = Yii::$app->authManager;

            $role = $auth->getRole($this->name);
            $user = User::findOne($this->id_user);

            if ($role && $user && $user->can($role->name)) {
                $auth->revoke($role, $user->id);
                $auth->invalidateCache();
                $this->id_user = null;
                return true;
            }
        }

        return false;
    }
}

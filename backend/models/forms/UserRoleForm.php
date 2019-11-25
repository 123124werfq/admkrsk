<?php

namespace backend\models\forms;

use common\models\User;
use Yii;
use yii\base\Model;

class UserRoleForm extends Model
{
    public $name;
    public $id_user;

    public function rules()
    {
        return [
            [['id_user'], 'integer'],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_user' => 'Пользователь',
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

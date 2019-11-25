<?php

namespace backend\models\forms;

use common\models\User;
use common\models\UserGroup;
use Yii;
use yii\base\Model;

class UserGroupForm extends Model
{
    public $id_user_group;
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
     */
    public function assign()
    {
        if ($this->validate()) {
            $userGroup = UserGroup::findOne($this->id_user_group);
            $user = User::findOne($this->id_user);

            if ($userGroup && $user) {
                $userGroup->link('users', $user);
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
            $userGroup = UserGroup::findOne($this->id_user_group);
            $user = User::findOne($this->id_user);

            if ($userGroup && $user) {
                $userGroup->unlink('users', $user, true);
                $this->id_user = null;
                return true;
            }
        }

        return false;
    }
}

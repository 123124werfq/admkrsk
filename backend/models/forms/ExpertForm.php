<?php

namespace backend\models\forms;

use common\models\User;
use common\models\HrExpert;
use Yii;
use yii\base\Model;

class ExpertForm extends Model
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


    public function promote()
    {
        if(!$this->validate())
            return false;

        $exp = HrExpert::findOne(['id_user' => $this->id_user]);

        if(!$exp){
            $exp = new HrExpert;
            $exp->id_user = $this->id_user;
            $exp->state = 1;
            if(!$exp->save())
                return false;
        }

        return $exp->id_expert;
    }

    public function dismiss()
    {
        $idexp = HrExpert::findOne(['id_user' => $this->id_user]);

        if(!$idexp)
            return false;

        if($idexp->delete())
            return true;

        return false;
    }

}

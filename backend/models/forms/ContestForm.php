<?php

namespace backend\models\forms;

use common\models\User;
use common\models\HrProfile;
use common\models\HrContest;

use Yii;
use yii\base\Model;

class ContestForm extends HrContest
{
    public $id;
    public $name;
    public $date_start;
    public $date_end;
    public $experts;
    public $moderator;
    public $profiles;
    public $notification;
    public $state;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'date_start', 'date_end', 'experts', 'profiles', 'moderators', 'state'], 'required'],
            [['notification'], 'string']

        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Заголовок',
            'date_start' => 'Дата начала',
            'date_end' => 'Дата завершения',
            'experts' => 'Эксперты',
            'moderator' => 'Модератор',
            'profiles' => 'Анкеты',
            'notification' => 'Текст нотификации'
        ];
    }

    public function create()
    {
        $contest = new HrContest;
        $contest->title = $this->name;
        $contest->begin = strtotime($this->date_start);
        $contest->end = strtotime($this->date_end);
        // доабвить модератора и нотификацию
        if($contest->save())
        {
            if(is_array($this->experts))
                foreach ($this->experts as $id_expert)
                {
                    $sql = "INSERT INTO hrl_contest_expert (id_contest, id_expert) VALUES ({$contest->id_contest}, {$id_expert})";
                    Yii::$app->db->createCommand($sql)->execute();
                }
            
            if(is_array($this->profiles))
                foreach ($this->profiles as $id_profile)
                {
                    $sql = "INSERT INTO hrl_contest_profile (id_contest, id_profile) VALUES ({$contest->id_contest}, {$id_profile})";
                    Yii::$app->db->createCommand($sql)->execute();
                }

            return $contest;
        }
        else {
            var_dump($contest->getErrors());
            //die();
        }
        return false;

    }

    public function getExperts()
    {
        return parent::getExperts();
    }

}

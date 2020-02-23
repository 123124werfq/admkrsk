<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cst_contest_expert".
 *
 * @property int|null $id_record_contest
 * @property int|null $id_expert
 * @property int|null $message_sent
 */
class CstContestExpert extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cst_contest_expert';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_record_contest', 'id_expert', 'message_sent'], 'default', 'value' => null],
            [['id_record_contest', 'id_expert', 'message_sent'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_record_contest' => 'Id Record Contest',
            'id_expert' => 'Id Expert',
            'message_sent' => 'Message Sent',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service_appeal_state".
 *
 * @property int $id_state
 * @property int $id_appeal
 * @property int $date
 * @property string $state
 */
class ServiceAppealState extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_appeal_state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_appeal', 'date'], 'default', 'value' => null],
            [['id_appeal', 'date'], 'integer'],
            [['state'], 'required'],
            [['state'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_state' => 'Id State',
            'id_appeal' => 'Id Appeal',
            'date' => 'Date',
            'state' => 'State',
        ];
    }
}

<?php

// Модель для сбора информации о состояниях заявок на получение муниципальных услуг


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

    const STATE_INIT = 0;
    const STATE_SEND = 1;
    const STATE_RESPONSE = 2;
    const STATE_CLOSED = 99;

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

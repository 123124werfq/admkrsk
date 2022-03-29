<?php

// Модель для сбора информации о подданых заявках на получение муниципальных услуг

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "service_appeal".
 *
 * @property int $id_appeal
 * @property int $id_user
 * @property int $id_service
 * @property string $state
 * @property int $date
 * @property string $data
 * @property int $archive
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class ServiceAppeal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_appeal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_service', 'id_record', 'date', 'archive', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id_target'], 'default', 'value' => null],
            [['id_user', 'id_service', 'id_record', 'date', 'archive', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id_target'], 'integer'],
            [['state'], 'required'],
            [['data', 'number_internal', 'number_system', 'number_common'], 'string'],
            [['state'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'id_service' => 'Id Service',
            'id_record' => 'ID record',
            'state' => 'Статус',
            'date' => 'Дата',
            'data' => 'Данные',
            'archive' => 'Archive',
            'created_at' => 'Дата создания ',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getCollectionRecord()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record']);
    }

     public function getTarget()
    {
        return $this->hasOne(ServiceTarget::class, ['id_target' => 'id_target']);
    }

    public function getService()
    {
        return $this->hasOne(Service::class, ['id_service' => 'id_service']);
    }

    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
        ];
    }

    public function getRecordData()
    {
        if(!$this->id_record)
            return false;

        $record = CollectionRecord::findOne($this->id_record);

        return $record->getData(true);
    }

/*
    public function getStatusName()
    {
        $as = ServiceAppealState::find()->where(['id_appeal' => $this->id_record])->orderBy('id_state DESC')->one();

        switch ($as)
        {
            case 0: return 'ОЖИДАЕТ РЕГИСТРАЦИИ';
            case 1: return 'ЗАРЕГИСТРИРОВАНО';
        }
    }
*/

    public function getStatusName()
    {
        $as = ServiceAppealState::find()->where(['id_appeal' => $this->id_appeal])->orderBy('id_state DESC')->one();

        if(!$as)
            return 'н/д';

        switch ($as->state) {
            case -1:
                $result = 'Ошибка обработки результата';
                break;
            case 0:
                $result = 'Черновик заявления. В процессе заполнения';
                break;
            case 1:
                $result = 'Принято от заявителя. Успешно зарегистрировано';
                break;                
            case 2:
                $result = 'Отправлено в ведомство. Заявление находится в процессе передачи в ведомство';
                break;
            case 3:
                $result = 'Исполнено. Дан ответ заявителю'; //(Для услуги "04/02/012"), Исполнено. Приглашаем Вас получить запрашиваемую услугу
                break;    
            case 4:
                $result = 'Отказ. Обращение не зарегистрировано, проверьте введенные данные';
                break;
            case 5:
                $result = 'Ошибка отправки в ведомство. Ошибка доставки формы. Попробуйте подать заявление повторно';
                break;
            case 6:
                $result = 'Принято ведомством. Заявление передано на рассмотрение исполнителю';
                break;
            case 7:
                $result = 'Промежуточные результаты от ведомства. Заявление получено ведомством';
                break;
            case 8:
                $result = 'Неизвестный статус';
                break;
            case 9:
                $result = 'В процессе отмены';
                break;
            case 10:
                $result = 'Отменено';
                break;
            case 11:
                $result = 'Неподтвержденная отмена';
                break;
            case 12:
                $result = 'Входящее Сообщение';
                break;
            case 14:
                $result = 'Ожидание доп. инфо от пользователя';
                break;
            case 14:
                $result = 'Ожидание доп. инфо от пользователя';
                break;
            case 15:
                $result = 'Заявка требует доп. корректировки';
                break;
            case 16:
                $result = 'Исходящее Сообщение';
                break;
            default:
                $result = 'Неизвестный статус';
                break;
        }

        return $result;
    }
    
    public function getStatusDate()
    {
        $as = ServiceAppealState::find()->where(['id_appeal' => $this->id_appeal])->andWhere(['state' => 1])->one();

        if(!$as)
            return "-";

        return date("d.m.Y", $as->date);

    }

    public function getStates()
    {
        return $this->hasMany(ServiceAppealState::class, ['id_appeal' => 'id_appeal']);
    }
}

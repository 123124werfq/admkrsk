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

    public function statusName()
    {
        $code = $this->state;

        $result = 'н/д';

        switch ($code) {
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
                $result = 'Исполнено. Дан ответ заявителю (Для услуги "04/02/012"), Исполнено. Приглашаем Вас получить запрашиваемую услугу';
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
}

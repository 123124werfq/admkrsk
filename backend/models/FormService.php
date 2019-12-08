<?php

namespace backend\models;

use Yii;
use yii\base\Model;

class FormService extends Model
{
    public  $id_service,
            $name,
            $fullname,
            $client_type;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_visibleinput';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_service', 'name', 'fullname'], 'required'],
            [['id_service'], 'integer'],
            [['name','fullname'], 'string'],
            [['client_type'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_service' => 'Услуга',
            'name' => 'Название',
            'fullname' => 'Полное название',
            'client_type' => 'Категория заявителя',
        ];
    }
}

<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_institution_document".
 *
 * @property int $id_institution_document
 * @property string $id_institution
 * @property string $type
 * @property string $name
 * @property string $url
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 *
 * @property Institution $institution
 */
class Document extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_institution_document';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_institution'], 'required'],
            [['id_institution'], 'string'],
            [['type', 'name', 'url'], 'string', 'max' => 255],
            [['id_institution'], 'exist', 'skipOnError' => true, 'targetClass' => Institution::class, 'targetAttribute' => ['id_institution' => 'id_institution']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_institution_document' => 'Id Institution Document',
            'id_institution' => 'Id Institution',
            'type' => 'Type',
            'name' => 'Name',
            'url' => 'Url',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInstitution()
    {
        return $this->hasOne(Institution::class, ['id_institution' => 'id_institution']);
    }
}

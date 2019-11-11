<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_pdocument_file".
 *
 * @property int $id_pdocument_file
 * @property int $id_pdocument
 * @property string $id_message
 * @property int $id_media
 * @property string $name
 * @property string $description
 * @property string $requestcode
 * @property string $digest
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class PdocumentFile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_pdocument_file';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pdocument', 'id_media', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_pdocument', 'id_media', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['id_message'], 'required'],
            [['description'], 'string'],
            [['id_message', 'name', 'requestcode', 'digest'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pdocument_file' => 'Id Pdocument File',
            'id_pdocument' => 'Id Pdocument',
            'id_message' => 'Id Message',
            'id_media' => 'Id Media',
            'name' => 'Name',
            'description' => 'Description',
            'requestcode' => 'Requestcode',
            'digest' => 'Digest',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }
}

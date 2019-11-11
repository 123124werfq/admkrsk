<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_pdocument_link".
 *
 * @property int $id_pdocument_link
 * @property int $id_pdocument
 * @property string $id_message
 * @property string $type
 * @property string $regnum
 * @property string $regdate
 * @property string $subject
 * @property string $linkname
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class PdocumentLink extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_pdocument_link';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pdocument', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_pdocument', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['id_message'], 'required'],
            [['subject'], 'string'],
            [['id_message', 'id_link', 'type', 'regnum', 'regdate', 'linkname'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pdocument_link' => 'Id Pdocument Link',
            'id_pdocument' => 'Id Pdocument',
            'id_message' => 'Id Message',
            'id_link' => 'Id',
            'type' => 'Type',
            'regnum' => 'Regnum',
            'regdate' => 'Regdate',
            'subject' => 'Subject',
            'linkname' => 'Linkname',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getDocument()
    {
        return $this->hasOne(Pdocument::class, ['id_message' => 'id_message']);
    }
}

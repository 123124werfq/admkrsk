<?php

namespace common\models;

use DateTime;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "db_opendata_structure".
 *
 * @property int $id_opendata_structure
 * @property int $id_opendata
 * @property string $signature
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property string $path
 * @property string $filename
 * @property string $datetime
 * @property string $url
 * @property array $metadata
 *
 * @property OpendataData $data
 * @property Opendata $opendata
 */
class OpendataStructure extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_opendata_structure';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_opendata', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_opendata', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['signature'], 'string'],
            [['id_opendata', 'signature'], 'unique', 'targetAttribute' => ['id_opendata', 'signature']],
            [['id_opendata'], 'exist', 'skipOnError' => true, 'targetClass' => Opendata::class, 'targetAttribute' => ['id_opendata' => 'id_opendata']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_opendata_structure' => 'Id Opendata Structure',
            'id_opendata' => 'Id Opendata',
            'signature' => 'Signature',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            //'ba' => BlameableBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        if (Yii::$app->publicStorage->has($this->path)) {
            Yii::$app->publicStorage->delete($this->path);
        }

        parent::afterDelete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getData()
    {
        return $this->hasMany(Opendata::class, ['id_opendata_structure' => 'id_opendata_structure']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOpendata()
    {
        return $this->hasOne(Opendata::class, ['id_opendata' => 'id_opendata']);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return 'opendata/' . $this->opendata->identifier . '/' . $this->filename . '.csv';
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return 'structure-' . $this->datetime;
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     */
    public function getDatetime()
    {
        return (new DateTime(Yii::$app->formatter->asDatetime($this->created_at)))->format('Ymd\THi');
    }

    /**
     * @return string|null
     */
    public function getUrl()
    {
        $url = null;

        if (Yii::$app->publicStorage->has($this->path)) {
            $url = Yii::$app->publicStorage->getPublicUrl($this->path);

            if (strpos($url, '127.0.0.1:9000') !== false) {
                return str_replace('127.0.0.1:9000', 'storage.admkrsk.ru', $url);
            }
        }

        return $url;
    }

    /**
     * @return array|null
     */
    public function getMetadata()
    {
        $metadata = null;

        if (Yii::$app->publicStorage->has($this->path)) {
            $metadata = Yii::$app->publicStorage->getMetadata($this->path);
        }

        return $metadata;
    }
}

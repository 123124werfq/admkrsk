<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $key
 * @property string $settings
 * @property integer $id_collection
 * @property integer $id_page
 * @property integer $created_at
 * @property integer $updated_at
 */
class SettingPluginCollection extends ActiveRecord
{
    public static function tableName()
    {
        return 'settings_plugin_collection';
    }

    /**
     * @param string $settings
     * @param $collectionId
     * @return string|null
     * @throws Exception
     */
    public static function setSettings(string $settings, $collectionId)
    {
        $setting = new SettingPluginCollection();
        $setting->key = Yii::$app->security->generateRandomString();
        $setting->id_collection = intval($collectionId);
        $setting->id_page = intval(Yii::$app->request->get('page_id', 0));
        $setting->settings = $settings;
        if ($setting->save()) {
            return $setting->key;
        };
        return null;
    }

    /**
     * @param string $key
     * @return static|null
     */
    public static function getSettings(string $key)
    {
        return SettingPluginCollection::findOne(['key' => $key]);
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['key', 'settings'], 'string'],
            [['created_at', 'id', 'created_at', 'id_collection', 'id_page'], 'integer'],
        ];
    }
}
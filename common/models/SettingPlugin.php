<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use common\components\softdelete\SoftDeleteTrait;
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
class SettingPlugin extends ActiveRecord
{
    use SoftDeleteTrait;

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
        $setting = new SettingPlugin();
        $setting->key = Yii::$app->security->generateRandomString();
        $setting->id_model_widget = intval($collectionId);
        //$setting->id_page = intval(Yii::$app->request->get('page_id', 0));
        $setting->settings = $settings;

        if ($setting->save())
            return $setting->key;

        return null;
    }

    public static function updateSettingsForModel(array $attributes, $model)
    {
        foreach ($attributes as $attr)
        {
            /*$oldCollectionSettingsKeys = $this->searchCollectionKeys($model->getOldAttribute($attr));
            $newCollectionSettingsKeys = $this->searchCollectionKeys($model->getAttribute($attr));
            */
            //$deleteKeys = array_diff($oldCollectionSettingsKeys, $newCollectionSettingsKeys);                        

            /*SettingPlugin::deleteAll([
                'key' => $deleteKeys,
            ]);*/
        }
    }

    /**
     * @param string $content
     * @return array
     */
    private function searchCollectionKeys($content)
    {
        $collectionSettingsKeys = [];

        if (!empty($content))
        {
            if (preg_match_all('/data-key=".*"/i', $content, $matches))
            {
                foreach ($matches[0] as $match) {
                    $key = preg_split('/data-key=/i', $match);
                    $collectionSettingsKeys[] = str_replace('"', '', $key[1]);
                }
            }
        }
        
        return $collectionSettingsKeys;
    }

    /**
     * @param string $key
     * @return static|null
     */
    public static function getSettings(string $key)
    {
        return SettingPlugin::findOne(['key' => $key]);
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

    public function getModel()
    {
        /*$model = new $this->model_class;
        $pk = $model->owner->tableSchema->primaryKey[0]();*/

        return $this->model_class::findOne((int)$this->id_model);
    }

    public function getCollection()
    {
        return $this->hasOne(Collection::className(), ['id_collection' => 'id_collection']);
    }

    public function getColumns()
    {
        $settings = json_decode($this->settings,true);

        $columns = [];

        if (!empty($settings['columns']))
        {
            foreach ($settings['columns'] as $key => $data)
            {
                $column = CollectionColumn::findOne($data['id_column']);

                if (!empty($column))
                    $columns[] = $column;
            }
        }

        return $columns;
    }
}
<?php

namespace common\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property Gallery[] $galleries
 */
class GalleryGroup extends ActiveRecord
{
    public static function tableName()
    {
        return 'galleries_groups';
    }

    public function rules()
    {
        return [
            [['id', 'created_at'], 'integer'],
            [['name'], 'string'],
        ];
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getGalleries()
    {
        return $this->hasMany(Gallery::class, ['id_gallery' => 'gallery_id'])
            ->viaTable(Gallery::GALLERIES_GROUPS_JUNCTION, ['gallery_group_id' => 'id']);
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название группы'
        ];
    }
}
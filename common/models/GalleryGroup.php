<?php

namespace common\models;

use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
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

    /**
     * @param GalleryGroup[] $groups
     * @param string|null $selectGroup
     * @return array
     */
    public static function prepareGroupsForPlugin($groups, $selectGroup = null)
    {
        $galleryGroupsData = [];
        $selectGroupData = [];
        foreach ($groups as $galleryGroup) {
            $galleryGroupData = [
                'text' => $galleryGroup->name,
                'value' => (string)$galleryGroup->id,
            ];
            /** @var GalleryGroup $galleryGroup */
            if ($galleryGroup->id == $selectGroup) {
                $selectGroupData = $galleryGroupData;
                continue;
            }
            $galleryGroupsData[] = $galleryGroupData;
        }
        array_unshift($galleryGroupsData, [
            'text' => '',
            'value' => '',
        ]);
        if ($selectGroupData) {
            array_unshift($galleryGroupsData, $selectGroupData);
        }
        return $galleryGroupsData;
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название группы'
        ];
    }
}
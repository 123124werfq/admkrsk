<?php

namespace common\models;

use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use common\components\yiinput\RelationBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

use common\components\softdelete\SoftDeleteTrait;
use common\traits\ActionTrait;

/**
 * @property integer $id
 * @property string $name
 * @property integer $created_at
 * @property Gallery[] $galleries
 */
class GalleryGroup extends ActiveRecord
{
    use ActionTrait;
    use SoftDeleteTrait;

    public static function tableName()
    {
        return 'galleries_groups';
    }

    public function rules()
    {
        return [
            [['created_at'], 'integer'],
            [['name'], 'string'],
        ];
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getGalleries()
    {
        return $this->hasMany(Gallery::class, ['id_gallery' => 'id_gallery'])
                ->viaTable(Gallery::GALLERIES_GROUPS_JUNCTION, ['gallery_group_id' => 'gallery_group_id'])
                ->join('INNER JOIN', Gallery::GALLERIES_GROUPS_JUNCTION, Gallery::GALLERIES_GROUPS_JUNCTION.'.id_gallery = db_gallery.id_gallery')
                ->orderBy(['ord' => SORT_ASC]);
            /*->viaTable(Gallery::GALLERIES_GROUPS_JUNCTION, ['gallery_group_id' => 'gallery_group_id'], function ($query) {
                $query->orderBy([Gallery::GALLERIES_GROUPS_JUNCTION.'.ord' => SORT_ASC]);
        })->orderBy('ord');*/
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
                'value' => (string)$galleryGroup->gallery_group_id,
            ];
            /** @var GalleryGroup $galleryGroup */
            if ($galleryGroup->gallery_group_id == $selectGroup) {
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
            'yiinput' => [
                'class' => RelationBehavior::class,
                'relations'=> [
                    'galleries'=>[
                        'modelname'=>'Gallery',
                        'jtable'=>Gallery::GALLERIES_GROUPS_JUNCTION,
                        'added'=>false,
                        'fields_dbl'=>['ord'],
                    ],
                ]
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'gallery_group_id'=>'id',
            'name' => 'Название группы'
        ];
    }
}
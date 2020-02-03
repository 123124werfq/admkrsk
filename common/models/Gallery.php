<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\components\yiinput\RelationBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\traits\AccessTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * This is the model class for table "db_gallery".
 *
 * @property int $id_gallery
 * @property int $id_page
 * @property string $name
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property array $access_user_ids
 * @property GalleryGroup[] $groups
 * @property Media[] $medias
 */
class Gallery extends ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;
    use AccessTrait;

    const VERBOSE_NAME = 'Галерея';
    const VERBOSE_NAME_PLURAL = 'Галереи';
    const TITLE_ATTRIBUTE = 'name';

    public $access_user_ids;
    public $access_user_group_ids;

    /**
     * junction table contains galleries groups
     */
    const GALLERIES_GROUPS_JUNCTION = 'galleries_groups_junction';

    /**
     * Contains accepted gallery groups
     *
     * @var array
     */
    public $galleryGroup;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_gallery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_page', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_page', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],

            [['access_user_ids', 'access_user_group_ids', 'galleryGroup'], 'each', 'rule' => ['integer']],
            ['access_user_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
            ['access_user_group_ids', 'each', 'rule' => ['exist', 'targetClass' => UserGroup::class, 'targetAttribute' => 'id_user_group']],
        ];
    }

    public function behaviors()
    {
        return [
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.gallery',
            ],
            'multiupload' => [
                'class' => \common\components\multifile\MultiUploadBehavior::class,
                'relations'=>
                [
                    'medias'=>[
                        'model'=>'Media',
                        'jtable'=>'dbl_gallery_media',
                        //'fk_cover' => 'id_media',
                    ],
                ],
                //'cover'=>'media'
            ],
            'yiinput' => [
                'class' => RelationBehavior::class,
                'relations'=> [
                    'partitions'=>[
                        'modelname'=>'Page',
                        'jtable'=>'dbl_gallery_page',
                        'added'=>false,
                    ],
                ]
            ],
        ];
    }

    /**
     * @param Gallery[] $galleries
     * @param string|null $selectGallery
     * @return array
     */
    public static function prepareGroupsForPlugin($galleries, $selectGallery = null)
    {
        $galleriesData = [];
        $selectedGallery = [];
        /** @var Gallery $gallery */
        foreach ($galleries as $gallery) {
            $galleryData = [
                'text' => $gallery->name,
                'value' => (string)$gallery->id_gallery,
            ];
            if ($selectGallery == $gallery->id_gallery) {
                $selectedGallery = $galleryData;
                continue;
            }
            $galleriesData[] = $galleryData;
        }
        array_unshift($galleriesData, [
            'text' => '',
            'value' => '',
        ]);
        if ($selectedGallery) {
            array_unshift($galleriesData, $selectedGallery);
        }
        return $galleriesData;
    }

    /**
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function updateGroups()
    {
        if (!$this->galleryGroup) {
            return $this->deleteGroups();
        }
        $oldGroups = $this->getGroups()
            ->select('id')
            ->asArray()
            ->column();
        $newGroups = array_diff($this->galleryGroup, $oldGroups);
        if ($newGroups) {
            $batchNewGroups = $this->batchGroups($newGroups);
            Yii::$app->db->createCommand()
                ->batchInsert(static::GALLERIES_GROUPS_JUNCTION,
                    [
                        'gallery_group_id',
                        'gallery_id',
                    ],
                    $batchNewGroups)
                ->execute();
        }
        $removeGroups = array_diff($oldGroups, $this->galleryGroup);
        if ($removeGroups) {
            $batchRemoveGroups = $this->batchGroups($removeGroups);
            Yii::$app->db->createCommand()
                ->delete(static::GALLERIES_GROUPS_JUNCTION,
                    [
                        'gallery_id' => $this->id_gallery,
                        'gallery_group_id' => $batchRemoveGroups,
                    ])
                ->execute();
        }
    }

    /**
     * @return int
     * @throws Exception
     */
    public function deleteGroups()
    {
        return Yii::$app->db->createCommand()
            ->delete(static::GALLERIES_GROUPS_JUNCTION,
                [
                    'gallery_id' => $this->id_gallery,
                ])
            ->execute();
    }

    private function batchGroups(array $groups)
    {
        return array_map(function ($group) {
            return [
                $group,
                $this->id_gallery
            ];
        }, $groups);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_gallery' => 'ID',
            'id_page' => 'Раздел',
            'name' => 'Название',
            'created_at' => 'Добавлено',
            'created_by' => 'Автор',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
            'galleryGroup' => 'Выберите группу для галлерии',
        ];
    }

    /**
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getGroups()
    {
        return $this->hasMany(GalleryGroup::class, ['id' => 'gallery_group_id'])
            ->viaTable(static::GALLERIES_GROUPS_JUNCTION, ['gallery_id' => 'id_gallery']);
    }

    public function getMedias()
    {
        return $this->hasMany(Media::class, ['id_media' => 'id_media'])->viaTable('dbl_gallery_media', ['id_gallery' => 'id_gallery']);
    }

    public function getPartitions()
    {
        return $this->hasMany(Page::class, ['id_page' => 'id_page'])->viaTable('dbl_gallery_page',['id_gallery'=>'id_gallery']);
    }
}

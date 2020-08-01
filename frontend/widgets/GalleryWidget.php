<?php

namespace frontend\widgets;

use common\components\helper\Helper;
use common\models\Gallery;
use common\models\GalleryGroup;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * This class use for represent gallery from plugin on page
 * @see Helper
 */
class GalleryWidget extends Widget
{
    public $attributes = [];
    public $id_gallery;
    public $limit;
    public $page;
    public $groupGalleryId;
    public $objectData; // данные CollectionRecord объекста если идет его рендер

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function run()
    {
        if (!empty($this->attributes))
        {
            if (!empty($this->attributes['id'])) {
                $this->id_gallery = (int)$this->attributes['id'];
            }

            if (!empty($this->attributes['limit'])) {
                $this->limit = (int)$this->attributes['limit'];
            }

            if (!empty($this->attributes['groupid'])) {
                $this->groupGalleryId = (int)$this->attributes['groupid'];
            }
        }

        if (!$this->groupGalleryId)
        {
            $groupGalleries = GalleryGroup::find()
                ->where(['id' => $this->groupGalleryId])
                ->with('galleries.medias')
                ->one();

            return $this->render('gallery/gallery-list', [
                'galleries' => $groupGalleries->galleries,
                'limit' => $this->limit,
            ]);
        }

        $model = Gallery::find()
            ->joinWith('medias')
            ->where(['db_gallery.id_gallery' => $this->id_gallery])
            ->one();

        if (empty($model) || empty($model->medias)) {
            return '';
        }

        return $this->render('gallery/gallery', [
            'gallery' => $model,
            'medias' => $model->medias,
            'limit' => $this->limit,
        ]);
    }
}

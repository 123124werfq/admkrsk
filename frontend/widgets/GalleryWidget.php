<?php
namespace frontend\widgets;

use Yii;
use common\models\Gallery;

class GalleryWidget extends \yii\base\Widget
{
    public $attributes = [];
    public $id_gallery;
    public $limit;

    public function run()
    {
        if (!empty($this->attributes))
        {
            if (!empty($this->attributes['id']))
                $this->id_gallery = (int)$this->attributes['id'];

            if (!empty($this->attributes['limit']))
                $this->limit = (int)$this->attributes['limit'];
        }

    	$model = Gallery::find()->joinWith('medias')->where(['db_gallery.id_gallery'=>$this->id_gallery])->one();

        if (empty($model) || empty($model->medias))
            return '';

        return $this->render('gallery',[
        	'gallery'=>$model,
            'medias'=>$model->medias,
            'limit'=>$this->limit,
        ]);
    }
}

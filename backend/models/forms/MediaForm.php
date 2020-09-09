<?php

namespace backend\models\forms;

use yii\base\Model;
use common\models\Media;
use common\components\multifile\MultiUploadBehavior;

class MediaForm extends Model
{
    public $id_media, $title, $author, $lightbox, $size=1,$showLegend;

    public function rules()
    {
        return [            
            [['lightbox','size'], 'integer'],
            [['title','author'], 'string'],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'id_media' => 'Файл',
            'title'=>'Подпись',
            'size'=>'Размер',
            'lightbox'=>'Увеличивать при нажатии',
            'showLegend'=>'Показывать подпись',
        ];
    }

    public function behaviors()
    {
        return [            
            'multiupload' => [
                'class' => MultiUploadBehavior::class,
                'relations'=>
                [
                    'media'=>[
                        'model'=>'Media',
                        'fk_cover' => 'id_media',
                        'cover' => 'media',
                    ],
                ],
                'cover'=>'media'
            ],
        ];
    }

    public function getMedia()
    {
        return Media::find()->where(['id_media'=>$this->id_media])->one();
    }
}

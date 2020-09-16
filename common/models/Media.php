<?php

namespace common\models;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

use Yii;
use yii\imagine\Image;

/**
 * This is the model class for table "cnt_media".
 *
 * @property int $id_media
 * @property int $type
 * @property int $size
 * @property string $name
 * @property int $width
 * @property int $height
 * @property int $duration
 * @property string $mime
 * @property string $extension
 * @property int $ord
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 *
 * @property string $url
 */
class Media extends \yii\db\ActiveRecord
{
    const SIZE_SMALL = 0;
    const SIZE_MEDIUM = 1;
    const SIZE_BIG = 2;

    public $file_path;
    public $cover;
    public $crop;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cnt_media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'size', 'width', 'height', 'duration', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['type', 'size', 'width', 'height', 'duration', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['is_private'],'boolean'],
            [['name'], 'required'],
            [['name', 'mime', 'extension', 'author'], 'string', 'max' => 255],
            [['description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_media' => 'ID',
            'type' => 'Тип',
            'size' => 'Размер',
            'name' => 'Название',
            'width' => 'Ширина',
            'height' => 'Высота',
            'duration' => 'Продолжительность',
            'mime' => 'MIME',
            'extension' => 'Расширение',
            'ord' => 'Ord',
            'is_private'=>'Приватный',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public static function getSize()
    {
        return [
            self::SIZE_SMALL => 'Маленькое (300px)',
            self::SIZE_MEDIUM => 'Среднее (600px)',
            self::SIZE_BIG => 'Большое (1900px)',
        ];
    }

    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
        ];
    }

    public function isImage()
    {
        return (!empty($this->width));
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            if (empty($this->hash))
                $this->hash = hash('joaat', time().$this->name.$this->extension.$this->size);
        }

        return true;
    }

    public function getImageAttributes($file,$post=array())
    {
        $filename = $file;

        if (!strpos($file, 'runtime/')>0)
            $file = Yii::getAlias('@webroot').$file;

        $this->name = $post['filename']??$filename;
        $this->description = $post['description']??null;

        // получаем атрибуты изображения
        if (!is_file($file))
            return false;

        $size = getimagesize($file);
        $ext = \common\components\helper\Helper::getImageExtention($size['mime']);

        if (empty($ext))
            $ext = substr($file, strrpos($file, '.')+1);

        if (!empty($size))
        {
            $this->width = $size[0];

            if ($size[1]>2147483647)
                $size[1] = 2147483647*2-$size[1];

            $this->height = abs($size[1]);
            $this->mime = $size['mime'];
        }

        $this->pagecount = (int)($post['pagecount']??0);
        $this->extension = $ext;
        $this->size = filesize($file);
        $this->ord = (isset($post['ord']))?(int)$post['ord']:'';
        $this->cover = (isset($post['cover']))?(int)$post['cover']:'';
        $this->crop = (isset($post['crop']))?$post['crop']:'';
        $this->type = (isset($post['value']))?2:1;

        $this->file_path = $file;
    }

    public function downloadName()
    {
        return str_replace(' ', '_', $this->name);
    }

    /**
    *   Сохраняет файл в папку согласно хешу
    **/
    public function saveFile($path='')
    {
        $root = Yii::getAlias('@webroot');

        //$this->extension = substr($this->file_path,strrpos($this->file_path,'.')+1);

        if (strpos($this->file_path, '://')==false)
        {
            if (empty($path))
                $path = $this->file_path;

            if (is_file($path))
            {
                $stream = fopen($path, 'r+');
                if ($this->is_private)
                    Yii::$app->privateStorage->writeStream($this->getFilePath(), $stream);
                else
                    Yii::$app->publicStorage->writeStream($this->getFilePath(), $stream);
                fclose($stream);
                //copy($path,$root.$this->getFilePath());
            }
        }
        else
            copy($this->file_path,$root.$this->getFilePath());
    }

    public function getFileName()
    {
        $path = $this->getFilePath();

        return substr($path, strrpos($path, '/')+1);
    }

    public function makeCrop($crop)
    {
        $ratio = $this->width/$crop['owidth'];

        $root = Yii::getAlias('@webroot');

        Image::crop(Image::autorotate($root.$this->getFilePath()),
            $crop['width']*$ratio,
            $crop['height']*$ratio,
            [
                $crop['left']*$ratio,
                $crop['top']*$ratio
            ]
        )->save($root.$this->getFilePath(),['quality' => 80]);
    }

    private function makePublic($url)
    {
        $ip = Yii::$app->request->userIP;

        if ($ip!='127.0.0.1')
            return str_replace('http://127.0.0.1:9000', 'https://storage.admkrsk.ru', $url);

        return $url;
    }

    public function getUrl()
    {
        if (!$this->is_private)
            $url = Yii::$app->publicStorage->getPublicUrl($this->getFilePath());
        else
            $url = Yii::$app->privateStorage->getPresignedUrl($this->getFilePath(),strtotime('+1 hour'));

        $url = str_replace('http://storage.admkrsk.ru', 'https://storage.admkrsk.ru', $this->makePublic($url));

        return $url;
    }

    public function getFilePath()
    {
        $root = Yii::getAlias('@webroot');

        // если это еще не сохраненное изображение
        if ($this->isNewRecord)
            return str_replace($root,'',$this->file_path);

        $url_piece = 'content/media/';
        $dir = $root.$url_piece;

        $file = md5($this->id_media);

        // разбиваем на вложенные две папки
        $level1 = substr($file,0,2);
        /*if (!is_dir($dir.$level1))
            mkdir($dir.$level1);*/

        $level2 = substr($file,2,2);
        /*if (!is_dir($dir.$level1.'/'.$level2))
            mkdir($dir.$level1.'/'.$level2);*/

        $filename = ($this->hash?:$this->id_media).'.'.$this->extension;

        return $url_piece.$level1.'/'.$level2.'/'.$filename;
    }

    public function showThumb($option,$size=null)
    {
        if ($size!==null)
        {
            if ($size == Media::SIZE_MEDIUM)
                $option['w'] = 600;
            elseif ($size == Media::SIZE_BIG)
                $option['w'] = 1900;
            else
                $option['w'] = 300;
        }

        if (!empty($this->url)&&empty($this->size))
            return $this->url;

        if (!empty($option))
            return $this->makeThumb($this->getFilePath(),$option);
        else
            return $this->getUrl();
    }

    public static function thumb($id_media, $size=0)
    {
        $media = Media::findOne($id_media);

        if (empty($media))
            return '';

        $options = [];

        if ($size == Media::SIZE_MEDIUM)
            $options['w'] = 600;
        elseif ($size == Media::SIZE_BIG)
            $options['w'] = 1900;
        else
            $options['w'] = 300;

        return $media->showThumb($options);
    }


    public function makeThumb($source, $options)
    {
        if (empty($options) || $this->extension == 'svg' || $this->extension == 'tif' || $this->extension == 'tiff' || $this->width<=$options['w'])
            return $this->getUrl();

        $path = str_replace($this->extension, implode('_', $options).'.'.$this->extension, $source);
        $path = str_replace('/', '_', $path);

        if (Yii::$app->publicStorage->has($path))
            return $this->makePublic(Yii::$app->publicStorage->getPublicUrl($path));

        /*$preview_path = '/assets/preview/';
        $root = Yii::getAlias('@webroot');
        $preview_dir = $root.$preview_path;
        $ext = substr($source,strrpos($source,'.'));

        $source_md5 = md5($source.serialize($options));

        // первые 3 символа
        $level1 = substr($source_md5,0,2);

        if (!is_dir($preview_dir.$level1))
            mkdir($preview_dir.$level1);

        // вторые три символа
        $level2 = substr($source_md5,2,2);
        if (!is_dir($preview_dir.$level1.'/'.$level2))
            mkdir($preview_dir.$level1.'/'.$level2);

        $ext = substr($source,strrpos($source,'.'));

        $url =  $level1.'/'.$level2.'/'.$source_md5.$ext;

        $newfile = $preview_dir.$url;

        if (is_file($newfile))
            return $preview_path.$url;

        if (!is_file($root.$source))
            return $preview_path.$url;

        if (empty($options['h']))
            $options['h'] = $this->height*$options['w']/$this->width;

        if (empty($options['w']))
            $options['w'] = $this->width*$options['h']/$this->height;*/

        /*$exif = exif_read_data($filename);

        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 3:
                    $image = imagerotate($image, 180, 0);
                    break;

                case 6:
                    $image = imagerotate($image, -90, 0);
                    break;

                case 8:
                    $image = imagerotate($image, 90, 0);
                    break;
            }
        }*/

        if (!Yii::$app->publicStorage->has($source))
            return false;

        if (empty($options['h']))
            $options['h'] = $this->height*$options['w']/$this->width;

        $stream = Yii::$app->publicStorage->readStream($source);
        Image::thumbnail(Image::autorotate($stream), $options['w'], $options['h'])->save(Yii::getAlias('@runtime').'/'.$path,['quality' => 80]);

        if (!is_file(Yii::getAlias('@runtime').'/'.$path))
            return 'Error, source file not found';

        $preview_stream = fopen(Yii::getAlias('@runtime').'/'.$path, 'r+');
        Yii::$app->publicStorage->writeStream($path, $preview_stream);
        fclose($preview_stream);

        unlink(Yii::getAlias('@runtime').'/'.$path);

        return  $this->makePublic(Yii::$app->publicStorage->getPublicUrl($path));
    }
}

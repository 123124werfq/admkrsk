<?php
namespace frontend\widgets;

use yii\base\Widget;
use common\models\Collection;

class MapWidget extends Widget
{
    public $page;

    public $attributes = [];

    public $id_collection;

    public $defaultOptions = [
        'width' => '100%',
        'height' => '300px',
        'zoom' => 12,
        'center_x' => '56.010563',
        'center_y' => '92.852572'


    ];

    public $objectData;

    public function run()
    {
        if (!empty($this->attributes))
        {
            /*if (!empty($this->attributes['key']))
            {
                $setting = SettingPlugin::find()->where(['key'=>$this->attributes['key']])->one();

                if (!empty($setting))
                    $this->attributes = json_decode($setting->settings,true);
            }*/

            if (!empty($this->attributes['id']))
                $this->id_collection = (int)$this->attributes['id'];
        }

        return $this->render('collection/map',[
            'id_collection' => $this->id_collection,
            'page'=>$this->page,
            'uniq_id'=>time().rand(0,9999),
            'options' => $this->defaultOptions
        ]);
    }
}

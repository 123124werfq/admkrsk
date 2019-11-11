<?php
namespace frontend\widgets;

use Yii;
use common\models\Collection;

class CollectionWidget extends \yii\base\Widget
{
    public $attributes = [];

    public $id_collection;
    public $columns = [];
    public $limit = 20;

    public function run()
    {
        if (!empty($this->attributes))
        {
            if (!empty($this->attributes['id']))
                $this->id_collection = (int)$this->attributes['id'];

            if (!empty($this->attributes['columns']))
                $this->id_collection = $this->attributes['columns'];
        }

    	$model = Collection::find()->where(['id_collection'=>$this->id_collection])->one();

        if (empty($model))
            return '';

        if (empty($this->columns))
            $columns = $model->getColumns()->indexBy('id_column')->all();
        else
            $columns = $model->getColumns()->where(['id_column'=>$this->this->columns])->indexBy('id_column')->all(0);

        if (empty($model))
    		return false;

        $allrows = $model->getData(array_keys($columns));

        return $this->render('collection',[
        	'model'=>$model,
            'columns'=>$columns,
            'allrows'=>$allrows,
        ]);
    }
}

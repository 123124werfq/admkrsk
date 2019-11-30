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

    public $template = 'table';

    public function run()
    {
        if (!empty($this->attributes))
        {
            if (!empty($this->attributes['id']))
                $this->id_collection = (int)$this->attributes['id'];

            if (!empty($this->attributes['columns']))
            {
                $this->columns = json_decode(str_replace("&quot;", '"', $this->attributes['columns']),true);
            }
        }

    	$model = Collection::find()->where(['id_collection'=>$this->id_collection])->one();

        if (empty($model) || empty($this->columns))
            return '';

        $query = $model->getDataQueryByOptions($this->columns)->limit(30);
        $columns = $query->columns;

        return $this->render($this->template,[
        	'model'=>$model,
            'columns'=>$columns,
            'allrows'=>$query->getArray(),
        ]);
    }
}

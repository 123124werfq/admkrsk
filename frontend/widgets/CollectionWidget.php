<?php
namespace frontend\widgets;

use Yii;
use common\models\Collection;
use yii\data\Pagination;

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

            if (!empty($this->attributes['template']))
                $this->template = $this->attributes['template'];

            if (!empty($this->attributes['columns']))
                $this->columns = json_decode(str_replace("&quot;", '"', $this->attributes['columns']),true);
        }

    	$model = Collection::find()->where(['id_collection'=>$this->id_collection])->one();

        if (empty($model) || empty($this->columns))
            return '';

        $p = (int)Yii::$app->request->get('page',0);

        $query = $model->getDataQueryByOptions($this->columns);
        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'route'=>Yii::$app->request->url
        ]);

        $query->offset($p*$pagination->limit)->limit($pagination->limit);
        $query->keyAsAlias = true;

        $columns = $query->columns;

        return $this->render('collection/'.$this->template,[
        	'model'=>$model,
            'page'=>$page,
            'pagination'=>$pagination,
            'columns'=>$columns,
            'allrows'=>$query->getArray(),
        ]);
    }
}

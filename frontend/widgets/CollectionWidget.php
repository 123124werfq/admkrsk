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
    public $group;
    public $sort;
    public $dir = SORT_ASC;
    public $page;

    public function run()
    {
        if (!empty($this->attributes))
        {
            if (!empty($this->attributes['id']))
                $this->id_collection = (int)$this->attributes['id'];

            if (!empty($this->attributes['template']))
                $this->template = $this->attributes['template'];

            if (!empty($this->attributes['sort']))
                $this->sort = (int)$this->attributes['sort'];

            if (!empty($this->attributes['dir']))
                $this->dir = (int)$this->attributes['dir'];

            if (!empty($this->attributes['group']))
                $this->group = (int)$this->attributes['group'];

            if (!empty($this->attributes['columns']))
                $this->columns = json_decode(str_replace("&quot;", '"', $this->attributes['columns']),true);
        }

    	$model = Collection::find()->where(['id_collection'=>$this->id_collection])->one();

        if (empty($model) || empty($this->columns))
            return '';

        $p = (int)Yii::$app->request->get('p',0);

        $query = $model->getDataQueryByOptions($this->columns);

        // сортировка
        if (!empty($this->sort))
            $query->orderBy(['col'.$this->sort=>$this->dir]);

        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'route'=>str_replace('?p='.$p,'',Yii::$app->request->url),
            'pageParam'=>'p'
        ]);

        $query->offset($p*$pagination->limit)->limit($pagination->limit);
        $query->keyAsAlias = true;

        $columns = $query->columns;

        $group_alias = false;
        
        if (!empty($this->group) && !empty($columns[$this->group]))
            $group_alias = $columns[$this->group]->alias;

        $allrows = $query->getArray();

        if ($this->group)
        {
            $group_rows = [];
            if (!empty($group_alias))
            {
                foreach ($allrows as $id_record => $row)
                    $group_rows[isset($row[$group_alias])?$row[$group_alias]:0][$id_record] = $row;
            }

            return $this->render('collection/group/'.$this->template,[
                'model'=>$model,
                'pagination'=>$pagination,
                'columns'=>$columns,
                'groups'=>$group_rows,
                'page'=>$this->page,
            ]);
        }

        return $this->render('collection/'.$this->template,[
        	'model'=>$model,
            'pagination'=>$pagination,
            'columns'=>$columns,
            'allrows'=>$allrows,
            'page'=>$this->page,
        ]);
    }
}

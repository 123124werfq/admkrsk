<?php
namespace frontend\widgets;

use Yii;
use common\models\Collection;
use yii\data\Pagination;

class CollectionWidget extends \yii\base\Widget
{
    public $attributes = [];

    public $id_collection;
    public $columns = []; // колонки для отображения
    public $search = []; // колонки для сортировки

    public $limit = 20; // записей на страницу

    public $template = 'table'; // шаблон отображения

    public $group; // группировка
    public $sort; // сортировка
    public $dir = SORT_ASC; // направление сортировки

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

            /*if (!empty($this->attributes['search']))
                $this->search = (int)$this->attributes['search'];*/

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

        $query = $model->getDataQueryByOptions($this->columns);

        // страница
        $p = (int)Yii::$app->request->get('p',1);

        // колонки коллекции
        $columns = $query->columns;

        $search_columns = [];
        if (!empty($this->columns['search']))
            foreach ($this->columns['search'] as $key => $column_search)
            {
                if (isset($columns[$column_search['id_column']]))
                {
                    $search_columns[$column_search['id_column']]['column'] = $columns[$column_search['id_column']];
                    $search_columns[$column_search['id_column']]['type'] = $column_search['type'];
                    $search_columns[$column_search['id_column']]['values'] = [];
                }
            }

        // массив сортировки
        $orderBy = [];

        // имя колонки группировки
        $group_alias = false;

        if (!empty($this->group) && !empty($columns[$this->group]))
        {
            $orderBy['col'.$this->group] = SORT_ASC;
            $group_alias = $columns[$this->group]->alias;
        }

        if (!empty($this->sort))
            $orderBy['col'.$this->sort] = $this->dir;

        // сортировка
        if (!empty($orderBy))
            $query->orderBy($orderBy);

        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'route'=>str_replace('?p='.$p,'',Yii::$app->request->url),
            'pageParam'=>'p'
        ]);

        //$query->offset(($p-1)*$pagination->limit)->limit($pagination->limit);
        $query->keyAsAlias = true;

        $allrows = $query->getArray();

        if (!empty($search_columns))
        {
            foreach ($allrows as $rkey => $row)
            {
                foreach ($search_columns as $key => $search_column)
                {
                    if ($search_column['type'] == 1)
                        continue;

                    $alias = $search_column['column']->alias;

                    if (!empty($row[$alias]) && (is_string($row[$alias]) || is_numeric($row[$alias])))
                    {
                        $search_columns[$key]['values'][$row[$alias]] = $row[$alias];
                    }
                }
            }
        }

        // переворачиваем колонки на алиас
        $columnsByAlias = [];

        foreach ($columns as $key => $col)
            $columnsByAlias[$col->alias] = $col;

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
                'columns'=>$columnsByAlias,
                'groups'=>$group_rows,
                'page'=>$this->page,
            ]);
        }

        $allrows = array_slice($allrows, ($p-1)*20, 20);

        return $this->render('collection/'.$this->template,[
        	'model'=>$model,
            'pagination'=>$pagination,
            'columns'=>$columnsByAlias,
            'allrows'=>$allrows,
            'search_columns'=>$search_columns,
            'page'=>$this->page,
        ]);
    }
}

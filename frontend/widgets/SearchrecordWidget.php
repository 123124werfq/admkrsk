<?php
namespace frontend\widgets;

use Yii;
use common\models\Collection;

class SearchrecordWidget extends \yii\base\Widget
{
    public $attributes = [];

    public $id_collection;

    public $columns = []; // колонки для отображения
    public $search = []; // колонки для сортировки

    public $pagesize = 20; // записей на страницу

    public $template = 'table'; // шаблон отображения

    public $table_head;
    public $table_style;

    public $group; // группировка
    public $sort; // сортировка
    public $dir = SORT_ASC; // направление сортировки

    public $link_column = false;
    public $show_download = false;

    public $objectData = []; // данные CollectionRecord объекста если идет его рендер

    public $page;

    public function run()
    {
        if (!empty($this->attributes))
        {
            if (!empty($this->attributes['id']))
                $this->id_collection = (int)$this->attributes['id'];

            if (!empty($this->attributes['id_collection']))
                $this->id_collection = (int)$this->attributes['id_collection'];

            if (!empty($this->attributes['show_download']))
                $this->show_download = (int)$this->attributes['show_download'];

            if (!empty($this->attributes['columns']))
            {
                if (is_array($this->attributes['columns']))
                    $this->columns = json_encode($this->attributes);
                else
                    $this->columns = str_replace("&quot;", '"', $this->attributes['columns']);

                foreach ($this->objectData as $key => $value)
                {
                    if (!is_array($value))
                        $this->columns = str_replace('{{'.$key.'}}', $value, $this->columns);
                }

                $this->columns = json_decode($this->columns,true);

            }

            if (!empty($this->attributes['link_column']))
                $this->link_column = (int)$this->attributes['link_column'];
        }

        $model = Collection::find()->where(['id_collection'=>$this->id_collection])->one();

        if (empty($model) || empty($this->columns))
            return '';

        // уникальный хэш для виджета PJAX, paginatinon и тп. переделать на более короткий
        $unique_hash = hash('joaat', $this->id_collection.serialize($this->columns));

        // mongo query
        $query = $model->getDataQueryByOptions($this->columns);

        // страница
        $p = (int)Yii::$app->request->get('p',1);

        // колонки коллекции
        $columns = $query->columns;

        // обработка поисковых колонок
        $search_columns = [];

        if (!empty($this->columns['search']))
            foreach ($this->columns['search'] as $key => $column_search)
            {
                if (isset($columns[$column_search['id_column']]))
                {
                    $search_columns[$column_search['id_column']]['column'] = $columns[$column_search['id_column']];
                    $search_columns[$column_search['id_column']]['type'] = $column_search['type'];

                    if (!empty($columns[$column_search['id_column']]->input->values))
                        $search_columns[$column_search['id_column']]['values'] = $columns[$column_search['id_column']]->input->getArrayValues();
                    else
                        $search_columns[$column_search['id_column']]['values'] = [];
                }
            }

        $runSearch = false;

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

                    // собираем все возможные значения для выпадшки в фильтре
                    if (!empty($row[$alias]) && (is_string($row[$alias]) || is_numeric($row[$alias])))
                        $search_columns[$key]['values'][$row[$alias]] = $row[$alias];
                }
            }
        }

        // добавляем поиск полученный из GET
        if (!empty($_GET['search_column'][$unique_hash]))
        {
            $search = $_GET['search_column'][$unique_hash];

            if (is_array($search))
            {
                foreach ($search as $id_col => $search_col)
                {
                    if (isset($search_columns[$id_col]) && $search_col!=='' && $search_col!==NULL)
                    {
                        $search_columns[$id_col]['value'] = $search_col;// (is_numeric($search_col))?(float)$search_col:$search_col;

                        if ($search_columns[$id_col]['type']==1)
                            $query->andWhere(['like','col'.$id_col,$search_columns[$id_col]['value']]);
                        else
                        {
                            $query->andWhere(['or',['col'.$id_col=>$search_columns[$id_col]['value']],['col'.$id_col=>(int)$search_columns[$id_col]['value']]]);
                        }

                        $runSearch = true;
                    }
                }
            }
        }

        if ($runSearch)
        {
            $query->keyAsAlias = false;
            $record = $query->limit(1)->getArray();

            if (!empty($record))
                $record = array_shift($record);
        }

        // переворачиваем колонки на алиас с очередностью выбора
        $columnsByAlias = [];
        $columnsOptions = [];

        /*foreach ($this->columns['columns'] as $key => $col)
        {
            if (!empty($columns[$col['id_column']]))
            {
                if (!empty($col['show_for_searchcolumn']) && is_array($col['show_for_searchcolumn']))
                {
                    $show = false;

                    foreach ($col['show_for_searchcolumn'] as $skey => $serchcol)
                    {
                        if (empty($serchcol) || !empty($search_columns[$serchcol]['value']))
                        {
                            $show = true;
                            break;
                        }
                    }

                    if (!$show)
                        continue;
                }

                // ставим alias заместо ID для ссылки на файл
                if (!empty($col['filelink']) && !empty($columns[$col['filelink']]))
                    $col['filelink'] = $columns[$col['filelink']]->alias;

                $columnsOptions[$columns[$col['id_column']]->alias] = $col;
                $columnsByAlias[$columns[$col['id_column']]->alias] = $columns[$col['id_column']];
            }
        }*/

        // обычное отображение
        return $this->render('collection_record_search',[
            'model'=>$model,
            'id_collection'=>$this->id_collection,
            'unique_hash'=>$unique_hash,
            'page'=>$this->page,

            'columns'=>$columns,
            'Record'=>$record??'',

            'search_columns'=>$search_columns,

            'show_download'=>$this->show_download,
        ]);
    }
}

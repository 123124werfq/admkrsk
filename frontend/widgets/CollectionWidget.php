<?php
namespace frontend\widgets;

use Yii;
use common\models\Collection;
use common\models\CollectionColumn;
use common\models\SettingPluginCollection;
use common\components\helper\Helper;
use yii\data\Pagination;

class CollectionWidget extends \yii\base\Widget
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
    public $show_row_num = false;
    public $show_column_num = false;
    public $show_download = false;
    public $show_on_map = 0; // отображать на карте

    public $objectData = []; // данные CollectionRecord объекста если идет его рендер

    public $page;

    public function run()
    {
        $setting = '';

        if (!empty($this->attributes))
        {
            if (!empty($this->attributes['key']))
            {
                $setting = SettingPluginCollection::find()->where(['key'=>$this->attributes['key']])->one();
                if (!empty($setting))
                    $this->attributes = json_decode($setting->settings,true);
            }

            if (!empty($this->attributes['id']))
                $this->id_collection = (int)$this->attributes['id'];

            if (!empty($this->attributes['id_collection']))
                $this->id_collection = (int)$this->attributes['id_collection'];

            if (!empty($this->attributes['template_view']))
                $this->template = $this->attributes['template_view'];

            if (!empty($this->attributes['id_column_order']))
                $this->sort = (int)$this->attributes['id_column_order'];

            if (!empty($this->attributes['order_direction']))
                $this->dir = (int)$this->attributes['order_direction'];

            if (!empty($this->attributes['pagesize']))
                $this->pagesize = (int)$this->attributes['pagesize'];

            if (!empty($this->attributes['show_column_num']))
                $this->show_column_num = (int)$this->attributes['show_column_num'];

            if (!empty($this->attributes['show_download']))
                $this->show_download = (int)$this->attributes['show_download'];

            if (!empty($this->attributes['show_row_num']))
                $this->show_row_num = (int)$this->attributes['show_row_num'];

            if (!empty($this->attributes['show_on_map']))
                $this->show_on_map = (int)$this->attributes['show_on_map'];

            if (!empty($this->attributes['table_head']))
                $this->table_head = $this->attributes['table_head'];

            if (!empty($this->attributes['table_style']))
                $this->table_style = $this->attributes['table_style'];

            if (!empty($this->attributes['group']))
                $this->group = (int)$this->attributes['group'];

            if (!empty($this->attributes['id_group']))
                $this->group = (int)$this->attributes['id_group'];

            if (!empty($this->attributes['columns']))
            {
                if (is_array($this->attributes['columns']))
                    $this->columns = json_encode($this->attributes);
                else
                    $this->columns = str_replace("&quot;", '"', $this->attributes['columns']);

                foreach ($this->objectData as $key => $value)
                {
                    if (!is_array($value))
                    {
                        if ($key=='id_record')
                        {
                            $this->columns = str_replace('\"{{'.$key.'}}\"', $value, $this->columns);
                            //$this->columns = str_replace('{{'.$key.'}}', $value, $this->columns);
                        }
                        else
                            $this->columns = str_replace('{{'.$key.'}}', $value, $this->columns);
                    }
                }

                $this->columns = json_decode($this->columns,true);

            }

            if (!empty($this->attributes['link_column']))
                $this->link_column = (int)$this->attributes['link_column'];
        }

    	$model = Collection::find()->where(['id_collection'=>$this->id_collection])->one();

        if (empty($model) || empty($this->columns))
            return '';

        if ($this->template!='table' && !empty($model->template_element))
        {
            $columns_alias = Helper::getTwigVars($model->template_element);

            if (!empty($columns_alias))
            {
                $addColumn = CollectionColumn::find()->where(['alias'=>$columns_alias])->indexBy('id_column')->all();

                foreach ($addColumn as $key => $column)
                    $this->columns['columns'][] = ['id_column'=>$column->id_column];
            }
        }

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
                        $search_columns[$id_col]['value'] = (is_numeric($search_col))?(float)$search_col:$search_col;

                        if ($search_columns[$id_col]['type']==1)
                            $query->andWhere(['like','col'.$id_col,$search_columns[$id_col]['value']]);
                        elseif ($search_columns[$id_col]['type']==3)
                        {
                            $dates = $search_columns[$id_col]['value'];
                            $dates = explode('-', $dates);

                            $begin = strtotime($dates[0]);
                            $end = strtotime($dates[1]);

                            if (count($dates)==2)
                            {
                                if ($columns[$id_col]->type==CollectionColumn::TYPE_REPEAT)
                                {

                                    $query->andWhere(['or',
                                        ['and',
                                            ['<=','col'.$id_col.'.begin',$begin],
                                            ['>=','col'.$id_col.'.end',$begin]
                                        ],
                                        ['and',
                                            ['<=','col'.$id_col.'.begin',$end],
                                            ['>=','col'.$id_col.'.end',$end]
                                        ],
                                        ['and',
                                            ['>=','col'.$id_col.'.begin',$begin],
                                            ['<=','col'.$id_col.'.end',$end]
                                        ]
                                    ])                                    
                                    ->andWhere(
                                        ['or',
                                            ['col'.$id_col.'_search'=>''],
                                            ['col'.$id_col.'_search'=>
                                                [
                                                    '$elemMatch'=>[
                                                        '$gte'=>$begin,
                                                        '$lte'=>$end,
                                                    ]
                                                ]
                                            ]
                                        ]
                                    );
                                }
                                else
                                {
                                    $query->andWhere(['and',
                                        ['<=','col'.$id_col,$begin],
                                        ['>=','col'.$id_col,$begin]
                                    ])
                                    ->orWhere(['and',
                                        ['<=','col'.$id_col,$end],
                                        ['>=','col'.$id_col,$end]
                                    ])
                                    ->orWhere(['and',
                                        ['>=','col'.$id_col,$begin],
                                        ['<=','col'.$id_col,$end]
                                    ]);
                                }
                            }
                        }
                        else
                            $query->andWhere(['col'.$id_col=>$search_columns[$id_col]['value']]);
                    }
                }
            }
        }

        // массив сортировки
        $orderBy = [];

        // имя колонки группировки
        $group_alias = false;

        // елси есть группа то сортируем сначало по группе
        if (!empty($this->group) && !empty($columns[$this->group]))
        {
            $orderBy['col'.$this->group] = SORT_ASC;
            $group_alias = $columns[$this->group]->alias;
        }

        // щатем добавляем сортировку которую задали
        if (!empty($this->sort))
            $orderBy['col'.$this->sort] = $this->dir;

        // сортировка
        if (!empty($orderBy))
            $query->orderBy($orderBy);

        // обработка url для пагинации с PJAX
        $url = parse_url(Yii::$app->request->url);

        if (!empty($_GET['ps']))
        {
            $ps = (int)$_GET['ps'];

            if ($ps>0 && $ps<=50)
                $this->pagesize = $ps;
        }

        if (!empty($url['query']))
        {
            parse_str($url['query'],$url_query);
            unset($url_query['p']);
            unset($url_query['ps']);
            unset($url_query['_pjax']);

            $url_query = http_build_query($url_query);
            if (!empty($url_query))
                $url = $url['path'].(strpos('?', $url_query)!==false?$url_query:'?'.$url_query);
            else
                $url = $url['path'];
        }
        else
            $url = Yii::$app->request->url;

        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'route'=>$url,
            'pagesize'=>$this->pagesize,
            'pageParam'=>'p',
            'pageSizeParam'=>'ps',
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
                    if ($search_column['type'] != 0)
                        continue;

                    $alias = $search_column['column']->alias;

                    // собираем все возможные значения для выпадшки в фильтре
                    if (!empty($row[$alias]) && (is_string($row[$alias]) || is_numeric($row[$alias])))
                        $search_columns[$key]['values'][$row[$alias]] = $row[$alias];
                }
            }
        }

        // проверяем существование отображения колонки-ссылки и ставим признак
        if (!empty($this->link_column) && !empty($columns[$this->link_column]))
            $columns[$this->link_column]->is_link = true;

        // переворачиваем колонки на алиас с очередностью выбора
        $columnsByAlias = [];
        $columnsOptions = [];

        foreach ($this->columns['columns'] as $key => $col)
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
        }

        // оффсет и срез данных
        $offset = ($p-1)*$this->pagesize;
        $allrows = array_slice($allrows, $offset, $this->pagesize,true);

        // отображение по группам
        if ($this->group)
        {
            $group_rows = [];

            if (!empty($group_alias))
            {
                foreach ($allrows as $id_record => $row)
                {           
                    $group_index = 0;

                    if ($columns[$this->group]->type==CollectionColumn::TYPE_REPEAT)
                    {
                        if (!empty($row[$group_alias]['begin']))
                            $group_index = date('d.m.Y',$row[$group_alias]['begin']).' - '.date('d.m.Y',$row[$group_alias]['end']);                        
                    }
                    else if (isset($row[$group_alias]))
                        $group_index = $row[$group_alias];

                    $group_rows[$group_index][$id_record] = $row;
                }
            }

            return $this->render('collection/group/'.$this->template,[
                'groups'=>$group_rows,
                'model'=>$model,
                'id_collection'=>$this->id_collection,
                'unique_hash'=>$unique_hash,
                'page'=>$this->page,

                'columns'=>$columnsByAlias,
                'columnsOptions'=>$columnsOptions,
                'allrows'=>$allrows,
                'search_columns'=>$search_columns,
                'show_on_map'=>(!empty($this->show_on_map) && !empty($model->id_column_map))?1:0,
                'table_head'=>$this->table_head,
                'table_style'=>$this->table_style,

                'pagesize'=>$this->pagesize,
                'pagination'=>$pagination,
                'offset'=>$offset,

                'show_row_num'=>$this->show_row_num,
                'show_column_num'=>$this->show_column_num,
            ]);
        }

        // обычное отображение
        return $this->render('collection/'.$this->template,[
        	'model'=>$model,
            'id_collection'=>$this->id_collection,
            'unique_hash'=>$unique_hash,
            'setting'=>$setting,
            'page'=>$this->page,

            'columns'=>$columnsByAlias,
            'columnsOptions'=>$columnsOptions,
            'allrows'=>$allrows,
            'search_columns'=>$search_columns,
            'show_download'=>$this->show_download,
            'show_on_map'=>(!empty($this->show_on_map) && !empty($model->id_column_map))?1:0,
            'table_head'=>$this->table_head,
            'table_style'=>$this->table_style,

            'pagesize'=>$this->pagesize,
            'pagination'=>$pagination,
            'offset'=>$offset,

            'show_row_num'=>$this->show_row_num,
            'show_column_num'=>$this->show_column_num,
        ]);
    }
}

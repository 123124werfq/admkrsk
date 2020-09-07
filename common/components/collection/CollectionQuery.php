<?php

namespace common\components\collection;

use Yii;
use common\models\Collection;
use common\models\CollectionColumn;
use common\models\CollectionRecord;

use yii\web\NotFoundHttpException;

class CollectionQuery extends \yii\mongodb\Query
{
    protected $_count;

    public $collection;
    public $columns;
    public $pagesize=30;
    public $ids = [];
    public $keyAsAlias;

    public $id_columns_search = [];

    public static function getQuery($id_collection)
    {
        $query = new CollectionQuery;
        $query->collection = Collection::findOneWithDeleted($id_collection);

        $query->from('collection'.$id_collection);
        //$query->andWhere(['=','date_delete',null]);

        $archiveColumn = $query->collection->getArchiveColumn();
        if (!empty($archiveColumn))
        {
            $attr = "col".$archiveColumn->id_column;
            $query->andWhere(['or',['=',$attr,null],[$attr=>0]]);
        }

        if (!empty($pagesize))
            $query->pagesize = $pagesize;

        return $query;
    }

    public function select(array $id_columns=[], array $id_columns_search=[])
    {
        $columns = $this->collection->getColumns()->with('input')->select(['alias','id_column','name','type','id_collection']);

        if (!empty($id_columns_search))
        {
            $this->id_columns_search = array_diff($id_columns_search, $id_columns);
            $id_columns = array_merge($id_columns,$id_columns_search);
        }

        if (!empty($id_columns))
            $columns->where(['id_column'=>$id_columns]);

        $columns = $columns->indexBy('id_column')->all();
        $this->columns = $columns;

        $select = [];
        foreach ($columns as $key => $column)
        {
            if (!empty($column->input->id_collection))
                $select[] = 'col'.$key.'_search';

            $select[] = 'col'.$key;
        }
        $select[] = 'id_record';
        $select[] = '_id';

        parent::select($select);

        return $this;
    }

/*    public function count($q = '*', $db = NULL)
    {
        if (empty($this->_count))
            $this->_count = parent::count();

        return $this->_count;
    }*/

    public function byID($id_record)
    {
        if (is_array($id_record))
        {
            $ids = [];
            foreach ($id_record as $key => $id)
                $ids[] = (int)$id;

            if (empty($ids))
                return $this;

            $id_record = $ids;
        }

        $this->andWhere(['id_record'=>$id_record]);

        return $this;
    }

    protected function GetIDColumnByAlias($alias)
    {
        foreach ($this->columns as $key => $column)
        {
            if ($column->alias == $alias)
                return $column->id_column;
        }

        return 0;
    }

    public function whereByAlias($condition=[])
    {
        if (count($condition)==1)
        {
            $alias = key($condition);
            $id_column = $this->GetIDColumnByAlias($alias);

            if (!empty($id_column))
                $this->andWhere(['col'.$id_column=>$condition[$alias]]);
        }
        else
        if (count($condition)==3)
        {
            $alias = $condition[1];
            $id_column = $this->GetIDColumnByAlias($alias);

            if (!empty($id_column))
                $this->andWhere([$condition[0],'col'.$id_column,$condition[2]]);
        }

        return $this;
    }

    public function orderByAlias(array $sorts)
    {
        $order = [];
        foreach ($sorts as $alias => $sort)
        {
            $id_column = $this->GetIDColumnByAlias($alias);

            if (!empty($id_column))
                $order['col'.$id_column] = $sort;

        }

        if (!empty($order))
            parent::orderBy($order);
    }

    public function getArray($keyAsAlias=false)
    {
        if ($keyAsAlias)
            $this->keyAsAlias = true;

        $output = [];
        $emptyRow = [];
        
        foreach ($this->columns as $key => $col)
        {
            if (!in_array($col->id_column, $this->id_columns_search))
                $emptyRow[$this->keyAsAlias?$col->alias:$col->id_column] = '';
        }

        foreach ($this->all() as $key => $record)
        {
            $output[$record['id_record']] = $emptyRow;

            foreach ($record as $vkey => $value)
            {
                $id_column = str_replace('col', '', $vkey);

                if (!isset($this->columns[$id_column])) //|| in_array($id_column, $this->id_columns_search)
                    continue;

                if (!empty($value))
                {
                    if (isset($record['col'.$id_column.'_search']))
                    {
                        if (!is_array($value))
                            $value = [$value];

                        $labels = json_decode($record['col'.$id_column.'_search'],true);

                        $combine_value = [];

                        foreach ($value as $ikey => $id)
                        {
                            if (empty($labels) && !empty($record['col'.$id_column.'_search']))
                                $combine_value[$id] = $record['col'.$id_column.'_search'];
                            else
                                $combine_value[$id] = $labels[$id]??$id;
                        }

                        $value = $combine_value;
                    }

                    // временное решение
                    if ($this->columns[$id_column]->type == CollectionColumn::TYPE_FILE_OLD)
                    {
                        $value = json_decode($value,true);

                        if (!empty($value))
                            $value = $value[0];
                    }
                }

                if ($this->keyAsAlias && !empty($this->columns[$id_column]->alias))
                    $alias = $this->columns[$id_column]->alias;
                else
                    $alias = $id_column;

                $output[$record['id_record']][$alias] = $value;
            }
        }

        return $output;
    }

    public function getObjects($keyAsAlias=false)
    {
        //$this->getArray
        foreach ($this->columns as $key => $col)
            $emptyRow[$this->keyAsAlias?$col->alias:$col->id_column] = '';
    }

    public function getStrinyfyArray()
    {
        $output = [];

        foreach ($this->getArray() as $id_record => $record)
        {
            foreach ($record as $rkey => $value)
            {
                if (is_array($value))
                    $output[$id_record][$rkey] = implode('<br/>', $value);
                else
                    $output[$id_record][$rkey] = $value;
            }
        }

        return $output;
    }

/*    public function getJson()
    {
        $id_user = Yii::$app->user->id;

        $offset = $this->setOffset();

        // подготавливаем JSON
        $json = [];

        foreach ($this->all() as $key => $data)
        {
            $json[] = [
                'id_picture'=>$data['id_picture'],
                'id_media' => $data['id_media_picture'],
                'key'=>$offset+$key,
                'post_url'=>$data['post_url'],
                'id_user'=>$data['id_user'],
                'user_url'=>'/'.$data['login'],
                'avatar'=>$data['id_media']?'https://s3-us-west-1.amazonaws.com/smupavatar/'.$data['id_user'].'.png':'/i/anon.png',
                'user_label'=>$data['login'],
                'time'=>Helper::getAgoTime($data['date_create']),
                'img'=>($id_user!=$data['id_user'] && $data['access']==Picture::ACCESS_FOLLOWERS && $this->followAccess)?'follow':Picture::getPreview($data,$width),//.'?'.$data['date_update'
                'width'=>$data['width'],
                'height'=>$data['height'],
                'description'=>Picture::taggedDescription('/search?tag=',$data['description']),
                'like_count'=>$data['like_count'],
                'crowd_count'=>$data['crowd_count'],
                'comment_count'=>intval($data['comment_count']),
                'is_favorite'=>(!isset($data['isFavorite']))?0:$data['isFavorite'],
                'translate'=> [
                    'comments' => \Yii::t('app', '{n,plural,=0{Comments} one{Comment} few{Comments} many{Comments} other{Comments}}', ['n' => intval($data['comment_count'])]),
                    'likes' => \Yii::t('app','Like')
                ]
            ];
        }

        return array_reverse($json);
    }*/
}
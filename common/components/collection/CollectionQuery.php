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

    public static function getQuery($id_collection)
    {
        $query = new CollectionQuery;
        $query->collection = Collection::findOneWithDeleted($id_collection);
        $query->from('collection'.$id_collection);

        if (!empty($pagesize))
            $query->pagesize = $pagesize;

        // сортировка по умолчанию
        $query->orderBy('id_record ASC');

        return $query;
    }

    public function select(array $id_columns=[])
    {
        $columns = $this->collection->getColumns()->select(['alias','id_column','name']);

        if (!empty($id_columns))
            $columns->where(['id_column'=>$id_columns]);

        $columns = $columns->indexBy('id_column')->all();
        $this->columns = $columns;

        $columns = array_keys($columns);
        $columns[] = 'id_record';
        $columns[] = '_id';

        parent::select($columns);

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
                $this->andWhere([$id_column=>$condition[$alias]]);
        }
        else
        if (count($condition)==3)
        {
            $alias = $condition[1];
            $id_column = $this->GetIDColumnByAlias($alias);

            if (!empty($id_column))
                $this->andWhere([$condition[0],$id_column,$condition[2]]);
        }

        return $this;
    }

    public function getArray()
    {
        $output = [];
        $emptyRow = [];

        foreach ($this->columns as $key => $col) {
            $emptyRow[$col->id_column] = '';
        }

        foreach ($this->all() as $key => $record)
        {
            $output[$record['id_record']] = $emptyRow;

            foreach ($record as $vkey => $value)
            {
                if (!isset($this->columns[$vkey]))
                    continue;

                if ($this->keyAsAlias && !empty($this->columns[$vkey]->alias))
                    $alias = $this->columns[$vkey]->alias;
                else
                    $alias = $vkey;

                $output[$record['id_record']][$alias] = $value;
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
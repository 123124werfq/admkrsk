<?php

namespace common\models\collection;

use common\models\CollectionColumn;
use common\models\Media;
use Yii;
use yii\data\ActiveDataProvider;
use yii\base\DynamicModel ;


class CollectionSearch extends DynamicModel
{
    public $collection;
    public $columns;
    //private $_properties;

    public function __construct($collection, $data=null, $config = [])
    {
        $attributes = [];

        $this->collection = $collection;

        $columns = $collection->getColumns()->indexBy('alias')->all();

        foreach ($columns as $column)
        {
            $attributes['col'.$column->id_column] = '';
        }

        parent::__construct($attributes, $config);

        foreach ($columns as $column)
        {
            switch ($column->type) {
                case CollectionColumn::TYPE_INTEGER:
                    $this->addRule(['col'.$column->id_column], 'number');
                    break;
                case CollectionColumn::TYPE_INPUT:
                    $this->addRule(['col'.$column->id_column], 'string');
                    break;
                default:
                    //$this->addRule(['col'.$column->id_column], 'safe');
                    break;
            }
        }
    }

    public function search($params)
    {
        $model = $this->collection;
        $query = $model->getDataQuery();

        $columns = $model->getColumns()->with('input')->all();

        $dataProviderColumns = [
            ['attribute'=>'id_record','label'=>'#'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<span class="btn btn-default">{view}</span><span class="btn btn-default update-record">{update}</span> <span class="btn btn-default">{delete}</span>',
                'contentOptions'=>['class'=>'button-column'],
                'urlCreator' => function ($action, $model, $key, $index)
                {
                    if ($action === 'update') {
                        $url ='update?id='.$model['id_record'];
                        return $url;
                    }
                    if ($action === 'view') {
                        $url ='view?id='.$model['id_record'];
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url ='delete?id='.$model['id_record'];
                        return $url;
                    }
                }
            ],
        ];

        $sortAttributes = ['id_record'];
        foreach ($columns as $key => $col)
        {
            $col_alias = 'col'.$col->id_column;

            $options = [];

            if (!empty($col->options['width']))
                $options['width'] = $col->options['width'].'px';

            $dataProviderColumns[$col_alias] =
            [
                'label'=>$col->name,
                'attribute'=>$col_alias,
                'format' => 'raw',
                'headerOptions'=>$options,
            ];

            /*if ($col->type==CollectionColumn::TYPE_INTEGER)
                $dataProviderColumns[$col_alias]['format'] = 'integer';*/

            if ($col->type==CollectionColumn::TYPE_DATE)
                $dataProviderColumns[$col_alias]['format'] = ['date', 'php:d.m.Y'];
            else if ($col->type==CollectionColumn::TYPE_DATETIME)
                $dataProviderColumns[$col_alias]['format'] = ['date', 'php:d.m.Y H:i'];
            else if ($col->type==CollectionColumn::TYPE_DISTRICT)
            {
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias) {
                    if (empty($model[$col_alias]))
                        return '';

                    $district = District::findOne($model[$col_alias]);

                    if (!empty($district))
                        return $district->name;

                    return '';
                };
            }
             else if ($col->type==CollectionColumn::TYPE_IMAGE)
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias,$col) {

                    if (empty($model[$col_alias]) || !is_array($model[$col_alias]))
                        return '';

                    return $col->getValueByType($model[$col_alias]);
                };
            }
            else if ($col->type==CollectionColumn::TYPE_FILE)
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias,$col) {

                    if (empty($model[$col_alias]) || !is_array($model[$col_alias]))
                        return '';

                    return $col->getValueByType($model[$col_alias]);
                };
            }
            else if ($col->type==CollectionColumn::TYPE_FILE_OLD)
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias) {

                    if (empty($model[$col_alias]))
                        return '';

                    $array = json_decode($model[$col_alias],true);

                    if (!empty($array))
                        return $output[] = '<a href="'.$array[0].'" download>'.$array[0].'</a>';
                    else
                        return $model[$col_alias];
                };
            }
            else if ($col->type==CollectionColumn::TYPE_ADDRESS)
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias) {

                    if (empty($model[$col_alias]))
                        return '';

                    //$array = json_decode($model[$col_alias],true);
                    $output = [];
                    $output[] = $model[$col_alias]['country']??'';
                    $output[] = $model[$col_alias]['region']??'';
                    $output[] = $model[$col_alias]['subregion']??'';
                    $output[] = $model[$col_alias]['city']??'';
                    $output[] = $model[$col_alias]['disctrict']??'';
                    $output[] = $model[$col_alias]['street']??'';
                    $output[] = $model[$col_alias]['house']??'';

                    return implode(',', $output);
                };
            }
            else if ($col->type==CollectionColumn::TYPE_COLLECTIONS)
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias)
                {
                    if (!empty($model[$col_alias.'_search']))
                    {
                        $labels = json_decode($model[$col_alias.'_search'],true);

                        if (is_array($labels))
                            return implode('<br>', $labels);
                    }

                    //$labels = json_decode($model[$col_alias],true);

                };
            }
            else if (!empty($col->input->id_collection))
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias)
                {
                    /*
                    if (empty($model[$col_alias]))
                        return '';

                    $labels = [];

                    if (!empty($model[$col_alias.'_search']))
                        $labels = explode(';', $model[$col_alias.'_search']);

                    $links = [];

                    if (is_array($model[$col_alias]))
                        foreach ($model[$col_alias] as $ckey => $label)
                        {
                            if (!empty($labels[$ckey]))
                                $links[] = '<a href="/collection-record/update?id='.$ckey.'">'.$label.'</a>';
                        }

                    return implode('<br>', $links);
                    */

                    if (empty($model[$col_alias]))
                        return '';

                    $labels = [];

                    if (!empty($model[$col_alias.'_search']))
                        $labels = explode(';', $model[$col_alias.'_search']);

                    $links = [];

                    if (is_array($model[$col_alias]))
                        foreach ($model[$col_alias] as $ckey => $id)
                        {
                            if (!empty($labels[$ckey]))
                                $links[] = '<a href="/collection-record/update?id='.$id.'">'.$labels[$ckey].'</a>';
                        }
                    else
                        $links = $labels;

                    return implode('<br>', $links);
                };
            }
            else
            {
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias)
                {
                    if (empty($model[$col_alias]))
                        return '';

                    if (is_array($model[$col_alias]))
                        return implode('<br>', $model[$col_alias]);
                    else
                        return $model[$col_alias];
                };
            }

            $sortAttributes[$col_alias] = [
                'asc' => [$col_alias => SORT_ASC],
                'desc' => [$col_alias => SORT_DESC],
                'default' => SORT_ASC
            ];
        }

        $this->columns = $dataProviderColumns;

        $this->load($params);

        foreach ($this->attributes as $attr => $value)
        {
            if (!empty($value))
                $query->andWhere(['or',['like',$attr,$value],[$attr=>(is_numeric($value))?(float)$value:$value]]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort' => [
                'attributes'=>$sortAttributes,
                'defaultOrder' => [
                    'id_record' => SORT_DESC
                ]
            ]
        ]);

        if (!$this->validate())
        {
            return $dataProvider;
        }

        /*$query->andFilterWhere([
            'id_house' => $this->id_house,
            'is_active' => $this->is_active,
            'is_updatable' => $this->is_updatable,
        ]);*/

        return $dataProvider;
    }
}

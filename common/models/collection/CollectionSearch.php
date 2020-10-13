<?php

namespace common\models\collection;

use common\models\CollectionColumn;
use common\models\Media;
use Yii;
use yii\data\ActiveDataProvider;
use yii\base\DynamicModel ;
use kartik\select2\Select2;
use yii\web\JsExpression;


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
                case CollectionColumn::TYPE_COLLECTION:
                case CollectionColumn::TYPE_COLLECTIONS:
                    $this->addRule(['col'.$column->id_column], 'number');
                    break;
                case CollectionColumn::TYPE_INPUT:
                case CollectionColumn::TYPE_TEXTAREA:
                case CollectionColumn::TYPE_RICHTEXT:
                case CollectionColumn::TYPE_CUSTOM:
                    $this->addRule(['col'.$column->id_column], 'string');
                    break;
                /*case CollectionColumn::TYPE_ARCHIVE:
                    $this->addRule(['col'.$column->id_column], 'boolean');
                    break;*/
                default:
                    //$this->addRule(['col'.$column->id_column], 'safe');
                    break;
            }
        }
    }

    public function search($params)
    {
        $model = $this->collection;
        $query = $model->getDataQuery(true);

        $columns = $model->getColumns()->with('input')->all();

        $dataProviderColumns = [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                       return ['value' => $model['id_record']];
                }
            ],
            [
                'attribute'=>'id_record',
                'format'=>'raw',
                /*'value'=>function($model)
                {
                    return '<span class="hidehover">'.$model['id_record'].'</span>';
                },*/
                'label'=>'#'
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<span class="btn btn-default">{view}</span><span class="btn btn-default update-record">{update}</span><span class="btn btn-default">{delete}</span>',
                'contentOptions'=>['class'=>'button-column'],
                'urlCreator' => function ($action, $record, $key, $index) use ($model)
                {
                    if ($action === 'update') {
                        $url ='update?id='.$record['id_record'];
                        return $url;
                    }
                    if ($action === 'view') {
                        $url ='view?id='.$record['id_record'].'&id_collection='.$model->id_collection;
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url ='delete?id='.$record['id_record'];
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
                $options['style'] = 'min-width:'.$col->options['width'].'px';
            else
            {
                if ($col->type==CollectionColumn::TYPE_TEXTAREA || $col->type==CollectionColumn::TYPE_RICHTEXT)
                    $options['style'] = 'min-width:500px';
                elseif ($col->type==CollectionColumn::TYPE_INPUT)
                    $options['style'] = 'min-width:200px';
                elseif ($col->type==CollectionColumn::TYPE_IMAGE)
                    $options['style'] = 'width:200px';
            }

            $dataProviderColumns[$col_alias] =
            [
                'label'=>$col->name,
                'attribute'=>$col_alias,
                'format' => 'raw',
                'headerOptions'=>$options,
            ];

            if ($col->type==CollectionColumn::TYPE_DATE)
                $dataProviderColumns[$col_alias]['format'] = ['date', 'php:d.m.Y'];
            elseif ($col->type==CollectionColumn::TYPE_TEXTAREA || $col->type==CollectionColumn::TYPE_RICHTEXT)
            {
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias) {
                    if (empty($model[$col_alias]))
                        return '';

                    if (mb_strlen($model[$col_alias])>400)
                    {
                        return '<div class="longtext">'.strip_tags($model[$col_alias]).'</div><a data-pjax="0" href="/collection-record/view?id='.$model['id_record'].'">Посмотреть все</';
                    }
                    else
                        return $model[$col_alias];
                };
            }
            else if ($col->type==CollectionColumn::TYPE_DATETIME)
                $dataProviderColumns[$col_alias]['format'] = ['date', 'php:d.m.Y H:i'];
            else if ($col->type==CollectionColumn::TYPE_DISTRICT)
            {
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias) {
                    if (empty($model[$col_alias]))
                        return '';

                    $district = \common\models\District::findOne($model[$col_alias]);

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
            else if ($col->type==CollectionColumn::TYPE_CHECKBOX || $col->type==CollectionColumn::TYPE_ARCHIVE)
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                $dataProviderColumns[$col_alias]['filter'] = [true => 'Да', false => 'Нет'];
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias,$col) {

                    if (empty($model[$col_alias]))
                        return '';

                    return 'Да';
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
                    $output[] = $model[$col_alias]['district']??'';
                    $output[] = $model[$col_alias]['street']??'';
                    $output[] = $model[$col_alias]['house']??'';


                    return implode(',', $output);
                };
            }
            else if ($col->type==CollectionColumn::TYPE_COLLECTIONS)
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                //$dataProviderColumns[$col_alias]['filter'] = Html::activeDropDownList($searchModel, 'attribute_name', ArrayHelper::map(ModelName::find()->asArray()->all(), 'ID', 'Name'),['class'=>'form-control','prompt' => 'Select Category']),

                $dataProviderColumns[$col_alias]['filter'] =
                Select2::widget([
                    'name' => 'attribute_name',
                    'value' => '',
                    'data' => [],
                    'pluginOptions' => [
                        'multiple' => false,
                        'allowClear' => true,
                        'minimumInputLength' => 2,
                        'placeholder' => 'Начните ввод',
                        'ajax' => [
                            'url' => '/record/list',
                            'dataType' => 'json',
                            'data' => new JsExpression('function(params) { return {q:params.term,id:'.$col->id_collection.',id_column:'.$col->input->id_collection_column.'};}')
                        ],
                    ],
                    'options'=>[
                        'prompt'=>'Выберите родителя'
                    ]
                ]);

                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias)
                {
                    if (!empty($model[$col_alias.'_search']))
                    {
                        $labels = json_decode($model[$col_alias.'_search'],true);

                        if (is_array($labels) && is_array($model[$col_alias]))
                        {
                            $links = [];
                            foreach ($model[$col_alias] as $key => $id_record)
                                $links[] = '<a data-pjax="0" target="_blank" href="/collection-record/view?id='.$id_record.'">'.$labels[$id_record].'</a>';

                            return implode('<br>', $links);
                        }
                    }
                };
            }
            else if ($col->type==CollectionColumn::TYPE_REPEAT)
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias,$col)
                {
                    if (empty($model[$col_alias]))
                        return '';

                    return $col->getValueByType($model[$col_alias]);
                };
            }
            else if (!empty($col->input->id_collection))
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias)
                {
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
                                $links[] = '<a data-pjax="0" target="_blank" href="/collection-record/view?id='.$id.'">'.$labels[$ckey].'</a>';
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

        $archiveColumn = $this->collection->getArchiveColumn();

        if (!empty($archiveColumn))
        {
            $archive = Yii::$app->request->get('archive');

            $attr = "col".$archiveColumn->id_column;

            if (empty($archive))
                $query->andWhere(['or',['=',$attr,null],[$attr=>0]]);
            else
                $query->andWhere([$attr=>1]);
        }

        foreach ($this->attributes as $attr => $value)
        {
            if ($value!='')
            {
                if (!empty($value))
                    $query->andWhere(['or',['like',$attr,$value],[$attr=>(is_numeric($value))?(int)$value:$value]]);
                else
                    $query->andWhere(['or',['=',$attr,null],[$attr=>(is_numeric($value))?(float)$value:$value]]);
            }
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

        return $dataProvider;
    }
}

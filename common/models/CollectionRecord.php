<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
use common\components\helper\Helper;
use yii\mongodb\Query;

/**
 * This is the model class for table "db_collection_record".
 *
 * @property int $id_record
 * @property int $id_collection
 * @property int $ord
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 *
 * @property Collection $collection
 */
class CollectionRecord extends \yii\db\ActiveRecord
{
    use SoftDeleteTrait;

    public $data = null;

    public $loadData = [];
    public $loadDataAlias = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_collection_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_collection', 'ord'], 'default', 'value' => null],
            [['id_collection', 'ord'], 'integer'],
            [['data_hash'], 'string'],
            [['data'],'safe'],
        ];
    }

    protected function getWeeknumbers($input)
    {
        $week = [];

        if (!is_array($input))
            return $week;

        foreach ($input as $key => $value)
        {
            switch ($value)
            {
                case 'Понедельник':
                    $week[] = 1;
                    break;
                case 'Вторник':
                    $week[] = 2;
                    break;
                case 'Среда':
                    $week[] = 3;
                    break;
                case 'Четверг':
                    $week[] = 4;
                    break;
                case 'Пятница':
                    $week[] = 5;
                    break;
                case 'Суббота':
                    $week[] = 6;
                    break;
                case 'Воскресенье':
                    $week[] = 7;
                    break;
            }
        }

        return $week;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_record' => 'Id Record',
            'id_collection' => 'Список',
            'ord' => 'Ord',
            'data_hash'=>'Хэш данных',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
            'log' => LogBehavior::class,
        ];
    }

    public function __get($name)
    {
        if (isset($this->loadDataAlias[$name]))
          return $this->loadDataAlias[$name];

       return parent::__get($name);
    }

    public function getLineValue($id_column=null)
    {
        $data = $this->getData();

        if (empty($id_column))
        {
            return implode(' ', $data);
        }
        else
            return $data[$id_column]??'Колонка не найдена';
    }

    protected function getLabelsByID($ids,$column)
    {
        $mongoLabels = [];

        if (!empty($ids))
        {
            $labels = (new \yii\db\Query())
                ->select(['value', 'id_record'])
                ->from('db_collection_value')
                ->where([
                    'id_record' => $ids,
                    'id_column'=>$column->input->id_collection_column
                ])->all();

            $labelsByIndex = [];

            foreach ($labels as $lkey => $data)
                $labelsByIndex[$data['id_record']] = $data['value'];

            if (is_array($ids))
                foreach ($ids as $key => $id)
                    $mongoLabels[$id] = $labelsByIndex[$id]??'';
            else
                $mongoLabels[$ids] = $labelsByIndex[$ids]??'';
        }

        return $mongoLabels;
    }

    public function getLabel()
    {
        $label = (!empty($this->collection->label)) ? $this->collection->label : [];

        $data = $this->getData(false);

        $output = [];

        foreach ($label as $key => $id_column) {
            if (!empty($data[$id_column]))
                $output[] = $data[$id_column];
        }

        return implode(', ',$output);
    }

    protected function getMongoDate($value, $column)
    {
        $output = [];

        $value_index = 'col'.$column->id_column;
        $search_index = 'col'.$column->id_column.'_search';

        $output[$value_index] = $value;

        switch ($column->type)
        {
            case CollectionColumn::TYPE_INTEGER:
                $output[$value_index] = (float)$value;
                break;
            case CollectionColumn::TYPE_CHECKBOXLIST:
                if (is_array($value))
                    $output[$search_index] = implode("\r\n", $value);
                else
                    $output[$search_index] = '';
                break;
            case CollectionColumn::TYPE_REPEAT:

            //var_dump($value);
                $dates = [];



                if (!empty($value['is_repeat']))
                {
                    $gbegin = $value['begin'];
                    $gend = $value['end'];

                    $repeat_count = $value['repeat_count']?:365;

                    if ($value['repeat']=='Ежедневно')
                    {
                        $space = (int)$value['day_space'];

                        $begin = $gbegin;

                        $i = 0;

                        while ($begin <= $gend && $i <= $repeat_count)
                        {
                            $search_date = $i*24*3600+$begin;

                            if ($search_date>=$gbegin)
                                $dates = [$search_date];

                            $begin+= ($space+1)*24*3600;

                            $i++;
                        }
                    }
                    else if ($value['repeat']=='Еженедельно')
                    {
                        $space = (int)$value['week_space'];
                        $week = $this->getWeeknumbers($value['week']??'');

                        $begin = $gbegin - (date('N',$gbegin))*24*3600;

                        $i = 1;
                        while ($begin <= $gend && $i <= $repeat_count)
                        {
                            foreach ($week as $wkey => $wkday)
                            {
                                $search_date = $wkday*24*3600+$begin;

                                if ($search_date>$gend)
                                    break;

                                if ($search_date>=$gbegin)
                                    $dates[] = $search_date;
                            }

                            if ($search_date>$gend)
                                break;

                            $begin += ($space+1)*7*24*3600;

                            $i++;
                        }
                    }
                    elseif ($value['repeat']=='Ежемесячно')
                    {
                        if ($value['repeat_month']=='Числа месяца')
                        {
                            $search_date = $begin = mktime(0,0,0,date('n',$gbegin),1,date('Y',$gbegin));

                            $i = 1;
                            while ($begin <= $gend && $search_date <= $gend && $i <= $repeat_count)
                            {
                                $t = date('t',$begin);

                                foreach ($value['month_days'] as $dkey => $day)
                                {
                                    if ($day>$t)
                                        continue;

                                    $search_date = $begin+$day*24*3600;

                                    if ($search_date>$gend)
                                        break;

                                    if ($search_date>=$gbegin && $search_date<=$gend)
                                    {
                                        $dates[] = $search_date;
                                        $i++;
                                    }
                                }

                                $begin = mktime(0,0,0,date('n',$begin)+1,1,date('Y',$begin));
                            }
                        }
                        elseif ($value['repeat_month']=='Неделя месяца')
                        {
                            $week_number = (int)$value['week_number']??'';

                            if (!empty($week_number))
                            {
                                $week = $this->getWeeknumbers($value['month_week']??'');

                                $search_date = $begin = mktime(0,0,0,date('n',$gbegin),1,date('Y',$gbegin))+($week_number-1)*7*24*3600;

                                $i = 1;

                                while ($begin <= $gend && $search_date <= $gend && $i <= $repeat_count)
                                {
                                    $begin = $begin - (date('N',$begin))*24*3600;

                                    foreach ($week as $wkey => $wkday)
                                    {
                                        $search_date = $wkday*24*3600+$begin;

                                        if ($search_date>$gend)
                                            break;

                                        if ($search_date>=$gbegin)
                                        {
                                            $dates[] = $search_date;
                                            $i++;
                                        }
                                    }

                                    if ($search_date>$gend)
                                        break;

                                    $begin = mktime(0,0,0,date('n',$begin)+1,1,date('Y',$begin))+($week_number-1)*7*24*3600;
                                }
                            }
                        }
                    }
                }

                $output[$search_index] = $dates;

                break;
            case CollectionColumn::TYPE_MAP:
                $output[$search_index] = implode(' ', $value);
                break;
            case CollectionColumn::TYPE_COLLECTION:
                $label = $this->getLabelsByID($value,$column);
                if (count($label)>0)
                    $output[$search_index] = array_shift($label);
                break;
            case CollectionColumn::TYPE_COLLECTIONS:
                $output[$search_index] = json_encode($this->getLabelsByID($value,$column),JSON_UNESCAPED_UNICODE);
                break;
            case CollectionColumn::TYPE_DISTRICT:
            case CollectionColumn::TYPE_STREET:
            //case CollectionColumn::TYPE_COUNTRY:
            case CollectionColumn::TYPE_CITY:
            case CollectionColumn::TYPE_REGION:
            case CollectionColumn::TYPE_SUBREGION:
            case CollectionColumn::TYPE_DATE:
            case CollectionColumn::TYPE_DATETIME:
            case CollectionColumn::TYPE_SERVICETARGET:
            case CollectionColumn::TYPE_SERVICE:
                $output[$search_index] = $column->getValueByType($value);
                break;
            case CollectionColumn::TYPE_FILE:
            case CollectionColumn::TYPE_IMAGE:
                $output[$search_index] = $value['name']??'';
                break;
                break;
            default:
                $output[$value_index] = $value;
                if (is_array($value))
                    $output[$search_index] = is_array($value)?json_encode($value,JSON_UNESCAPED_UNICODE):$value;

                break;
        }

        return $output;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert) && !empty($this->data))
            $this->data_hash = md5(json_encode($this->data));

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!empty($this->data)) //&& (empty($changedAttributes['data_hash']) || $insert)
        {
            $columns = $this->collection->getColumns()->with(['input'])->all();

            // коллекция монги
            $collection = Yii::$app->mongodb->getCollection('collection'.$this->id_collection);

            unset($this->data['id_record']);

            // запись в postgree
            $insertData = [];
            $dataMongo = [];

            foreach ($columns as $key => $column)
            {
                $value = '';

                if (isset($this->data[$column->alias]))
                    $value = $this->data[$column->alias];
                elseif (isset($this->data[$column->id_column]))
                    $value = $this->data[$column->id_column];
                else
                    continue;

                if (!$insert)
                {
                    $count = Yii::$app->db->createCommand("SELECT count(*) FROM db_collection_value WHERE id_record = $this->id_record AND
                        id_column = $column->id_column")->queryScalar();
                }

                if (!$insert && !empty($count))
                    Yii::$app->db->createCommand()->update('db_collection_value',
                        ['value'=>is_array($value)?json_encode($value):$value],[
                        'id_record'=>$this->id_record,
                        'id_column'=>$column->id_column
                    ])->execute();
                else
                    Yii::$app->db->createCommand()->insert('db_collection_value',[
                        'id_column'=>$column->id_column,
                        'id_record'=>$this->id_record,
                        'value'=>(is_array($value))?json_encode($value):$value,
                    ])->execute();

                $dataMongo = array_merge($dataMongo,$this->getMongoDate($value,$column));
            }

            $dataMongo['id_record'] = $this->id_record;

            if ($insert)
                $collection->insert($dataMongo);
            else
                $collection->update(['id_record'=>$this->id_record],$dataMongo);

            // Это надо оптимизировать, перенесено под инсерт потомучто не работает при CREATE / MSD
            $dataMongo = [];
            $columnsAlias = [];

            $hasCustom = false;
            // собираем кастомные колонки
            foreach ($columns as $key => $column)
            {
                if ($column->isCustom())
                {
                    $hasCustom = true;
                    $columnsAlias = array_merge(Helper::getTwigVars($column->template),$columnsAlias);
                }
            }

            if ($hasCustom)
            {
                $recordData = $this->getDataRaw(true,true,$columnsAlias);

                foreach ($columns as $key => $column)
                    if ($column->isCustom())
                        $dataMongo['col'.$column->id_column] = CollectionColumn::renderCustomValue($column->template,$recordData,$columnsAlias);
            }

            if (!empty($dataMongo))
                $collection->update(['id_record'=>$this->id_record],$dataMongo);
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /*public function updateCustomColumn($column)
    {
        $recordData = $this->getData(true);
    }*/

    public function getDataRaw($keyAsAlias=true,$includeRelation=false,$onlyColumns=[])
    {
        $record = \common\components\collection\CollectionQuery::getQuery($this->id_collection)
                    ->select()
                    ->where(['id_record'=>$this->id_record]);

        $columns = $record->columns;

        $record = $record->getArray();

        $output = ['id_record'=>$this->id_record];

        if (!empty($record))
        {
            $record = array_shift($record);

            foreach ($columns as $key => $column)
            {
                if (!empty($onlyColumns) && !in_array($column->alias, $onlyColumns))
                    continue;

                $value = $record[$column->id_column];

                if ($includeRelation && $column->isRelation() && !empty($value))
                {
                    if ($column->type == CollectionColumn::TYPE_COLLECTION)
                    {
                        $subrecord = CollectionRecord::findOne(key($value));
                        $output[$column['alias']] = $subrecord->getDataRaw($keyAsAlias,false);
                    }
                    else if ($column->type == CollectionColumn::TYPE_COLLECTIONS)
                    {
                        $output[$column['alias']] = [];

                        foreach ($value as $id_record => $label)
                        {
                            $subrecord = CollectionRecord::findOne($id_record);
                            $output[$column['alias']][$id_record] = $subrecord->getDataRaw($keyAsAlias,false);
                        }
                    }
                    else
                        $output[$column['alias']] = $value;
                }
                else
                {
                    if ($column->type == CollectionColumn::TYPE_JSON)
                        $output[$column['alias']] = json_decode($valu)
                    else
                        $output[$column['alias']] = $value;
                }
            }

            return $output;
        }
        else
            return [];
    }

    public function getDataAsString($keyAsAlias=true,$includeRelation=false,$onlyColumns=[])
    {
        $record = \common\components\collection\CollectionQuery::getQuery($this->id_collection)
                    ->select()
                    ->where(['id_record'=>$this->id_record]);

        $columns = $record->columns;

        $record = $record->getArray();

        $output = ['id_record'=>$this->id_record];

        if (!empty($record))
        {
            $record = array_shift($record);

            foreach ($columns as $key => $column)
            {
                if (!empty($onlyColumns) && !in_array($column->alias, $onlyColumns))
                    continue;

                $value = $record[$column->id_column];

                if ($includeRelation && $column->isRelation() && !empty($value))
                {
                    if ($column->type == CollectionColumn::TYPE_COLLECTION)
                    {
                        $subrecord = CollectionRecord::findOne(key($value));
                        $output[$column['alias']] = $subrecord->getDataAsString($keyAsAlias,false);
                    }
                    else if ($column->type == CollectionColumn::TYPE_COLLECTIONS)
                    {
                        $output[$column['alias']] = [];

                        foreach ($value as $id_record => $label)
                        {
                            $subrecord = CollectionRecord::findOne($id_record);
                            $output[$column['alias']][$id_record] = $subrecord->getDataAsString($keyAsAlias,false);
                        }
                    }
                    else
                        $output[$column['alias']] = $column->getValueByType($value);
                }
                else
                {
                    $output[$column['alias']] = $column->getValueByType($value);
                }
            }

            return $output;
        }
        else
            return [];
    }

    public function getData($keyAsAlias=false,$id_columns=[])
    {
        if ($this->isNewRecord)
            return [];

        $record = \common\components\collection\CollectionQuery::getQuery($this->id_collection)
                    ->select()
                    ->where(['id_record'=>$this->id_record]);

        $columns = $record->columns;

        $record = $record->getArray();

        if (!empty($record))
        {
            $record = array_shift($record);

            foreach ($columns as $key => $column)
                $this->loadDataAlias[$column['alias']] = $record[$column->id_column];

            $this->loadData = $record;
        }
        else
            return [];

        /*$rows = (new \yii\db\Query());

        $rows = $rows->select(['dcv.id_column', 'value','id_record','dcc.alias as alias'])
                ->from('db_collection_value as dcv')
                ->join('INNER JOIN', 'db_collection_column as dcc', 'dcc.id_column = dcv.id_column')
                ->where(['id_record'=>$this->id_record]);

        if (!empty($columns))
            $rows->andWhere(['dcv.id_column'=>$id_columns]);*/

        if (!empty($keyAsAlias))
        {
            /*$aliased = [];

            foreach ($columns as $key => $column)
                $aliased[$column['alias']] = $record[$column->id_column];*/

            return $this->loadDataAlias;
        }
        else
            return $record;
    }

    public function getAllMedias()
    {
        $output = [];

        foreach ($this->collection->columns as $key => $column)
        {
            if ($column->type == CollectionColumn::TYPE_FILE || $column->type == CollectionColumn::TYPE_IMAGE)
            {
                $medias = $this->getMedia($column->id_column);

                if (!empty($medias))
                    foreach ($medias as $key => $media)
                        $output[] = $media->getUrl();
            }
        }

        return $output;
    }

    public function getMedia($id_column, $firstElement=false)
    {
        if (empty($this->loadData))
            $this->getData();

        if (!empty($this->loadData[$id_column]))
        {
            //$ids = json_decode($this->loadData[$id_column],true);
            $value = $this->loadData[$id_column];

            if (!empty($value[0]['id']))
            {
                $ids = [];
                foreach ($value as $key => $data) {
                    $ids = $data['id'];
                }

                $value = $ids;
            }

            $medias = Media::find()->where(['id_media'=>$value])->all();

            if (!empty($medias))
                return ($firstElement)?array_shift($medias):$medias;
        }

        return null;
    }

    /*public function afterDelete()
    {
        $collection = Yii::$app->mongodb->getCollection('collection'.$this->id_collection);
        $collection->update(['id_record'=>$this->id_record],['date_delete'=>$this->date_delete]);

        return true;
    }*/

    protected function getLoadData()
    {
        if (empty($this->loadData))
            $this->getData();

        return $this->loadData;
    }

    public function getCollection()
    {
        return $this->hasOne(Collection::class, ['id_collection' => 'id_collection']);
    }

    public function afterDelete()
    {
        $collection = Yii::$app->mongodb->getCollection('collection'.$this->id_collection);
        $collection->remove(['id_record'=>$this->id_record]);

        parent::afterDelete();
    }
}

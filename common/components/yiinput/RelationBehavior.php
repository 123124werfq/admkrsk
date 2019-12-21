<?php
/**
 * RelationBehavior class file.
 *
 * @author Lobzenko Mikhail
 */

namespace common\components\yiinput;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Behavior;

class RelationBehavior extends Behavior
{
	/**
	 * @var array Имя таблицы
	 */
	public $relations = [];

    /**
     * @var array Имя primary key
     */
	public $pk_field;

    /**
     * @var integer Индекс модели при сохраненнии нескольких одинаковых моделей
     */
	public $index = -1;

    /**
     * @var array Записи
     */
	protected $records = [];

	public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
        ];
    }

    protected function getRelationByName($name)
    {
    	$relationQuery = 'get'.ucwords($name);

    	return $this->owner->$relationQuery();
    }

	/**
	 * Загрузить записи по имени модели
	 *
	 * @param string $relation
	 */
	protected function loadRecords($relation)
	{
		//$this->pk_field = $this->owner->primaryKey();
		// получаем настройки релейшена
		$behavior = $this->relations[$relation];

		if (!empty($this->owner->{$relation}))
			$this->records[$relation] = $this->owner->{$relation};
        else {
			$relationQuery = $this->getRelationByName($relation);
			$this->records[$relation] = [new $relationQuery->modelClass];
		}

		// добавляем дополнительные данные из кросс-таблицы
		if (isset($behavior['fields_dbl'])) {
			foreach ($this->records[$relation] as &$record) {
				$sql = "SELECT * FROM {$behavior['jtable']}
							WHERE
								{$record->tableSchema->primaryKey[0]} = ".(int)$record->primaryKey."
							AND ".$this->owner->tableSchema->primaryKey[0]." = ".(int)$this->owner->primaryKey;
				$row = Yii::$app->db->createCommand($sql)->queryOne();

				foreach ($behavior['fields_dbl'] as $field)
					$record->$field = $row[$field];
			}
		}

		return $this->records[$relation];
	}

    /**
     * Возвращает полученные записи
     *
     * @param string $relation
     * @return mixed
     * @throws \yii\db\Exception
     */
	public function getRecords($relation)
	{
		// если записи уже загружены например из post
		if (isset($this->records[$relation]))
            return $this->records[$relation];

        return $this->loadRecords($relation);
	}

    /**
     * Устанавливает индекс модели
     *
     * @param int $index
     * @return int
     */
	public function setModelIndex($index)
	{
		return $this->index = (int) $index;
	}

    /**
     * Получить массив из primary key релейшена
     *
     * @param string $relation
     * @return array
     * @throws \yii\db\Exception
     */
	public function getArrayPkValue($relation)
	{
		$this->loadRecords($relation);

		$output = [];

		if (isset($this->records[$relation])) {
			foreach ($this->records[$relation] as $data) {
				$output[] = $data->primaryKey;
            }
		}

		return $output;
	}

    /**
     * @param $event
     * @return bool
     */
    public function beforeValidate($event)
	{
		//$relations  = $this->getOwner()->relations();
		$owner_pk_name = $this->owner->tableSchema->primaryKey[0];
		$owner_validate = true;

		foreach ($this->relations as $relation_name => $relation) {
			$validation = true;

			// Имя класса
			$model_name = $relation['modelname'];

			// берем нужные нам POST
			$post = $this->getPOST($model_name, $relation_name);

			if ($post!==false)
			{
				// очищаем записи
				$this->records[$relation_name] = [];

				foreach ($post as $post)
				{
					$class = "common\models\\".$model_name;
					$object = new $class;
					$default_attributes = $object->attributes;
					$pk_field = $object->primaryKey()[0];

					// ищем по ПК если передали ПК
					if (!empty($post[$pk_field]))
						$find_object = $object->findOne($post[$pk_field]);
					else
						$find_object = null;

					// записываем данные из POST
					if (!empty($find_object))
					{
						$find_object->attributes = $post;
						$this->records[$relation_name][] = $find_object;

						if (($default_attributes != $find_object->attributes) || !empty($relation['validated']) || !empty($relation['required'])) {
							$validation = $validation && $find_object->validate();
							/*if (!$validation)
								print_r($object->errors);*/
						}
					}
					else
					{
						$object->attributes = $post;
						$this->records[$relation_name][] = $object;
                    }
				}

				if (isset($relation['required']) && (empty($this->records[$relation_name]) || !$validation)) {
					$this->owner->addError($owner_pk_name,$relation['required']);
					$owner_validate = false;
				}

				if (isset($relation['validated']) && !$validation) {
					$this->owner->addError($owner_pk_name,'Ошибка в заполнении данных');
				}

				$owner_validate = $owner_validate && $validation;
			}
		}

		if (!$owner_validate) {
			return false;
        }

		return true;
	}

    /**
     * @param $model_name
     * @param $relation_name
     * @return bool
     */
    protected function getPOST($model_name, $relation_name)
	{
		if ($this->index != -1) {
			if (isset($_POST[$model_name][$this->index][$relation_name])) {
				return $_POST[$model_name][$this->index][$relation_name];
            }
		} else {
			if (isset($_POST[$model_name][$relation_name])) {
				return $_POST[$model_name][$relation_name];
            }
        }

		return false;
	}

    /**
     * @param $event
     * @throws \yii\db\Exception
     */
    public function afterSave($event)
	{
		$pk_field = $this->owner->primaryKey()[0];
		$pk_value = $this->owner->primaryKey;
		//$relations 		= $this->getOwner()->relations();

		if (!isset($_POST)) {
			return;
        }

		foreach ($this->relations as $relation_name => $relation) {
			// получаем имя модели
			$model_name = $relation['modelname'];

			$POST = $this->getPOST($model_name, $relation_name);

			// если с POST к нам ничего не пришло выходим
			if ($POST === false) {
				continue;
            }

			// создаем объект класса
			$class = "common\models\\" . $model_name;
			$model_obj = new $class;

			// поле PK для объекта
			$model_pk_field = $model_obj->primaryKey()[0];

			// имя таблицы
			$table 	= $model_obj->tableName();

			// если релейшен с кростаблицей
			if (!empty($relation['jtable']))
			{
				$dbl_table = $relation['jtable'];

				// очищаем таблицу линков
				$sql = "DELETE FROM $dbl_table
							WHERE $pk_field = $pk_value";
				Yii::$app->db->createCommand($sql)->execute();

				$this->records[$relation_name] = '';

				// начинаем заполнять данные
				foreach ($POST as $record) {
					// устанавливаем ключ
					$insert = [];

					// удаляем поля из POST которые относятся к dbl таблице
					$record_search = $record;
					if (!empty($relation['fields_dbl'])) {
						foreach ($relation['fields_dbl'] as $field) {
							unset($record_search[$field]);
                        }
                    }

					// ищем по введеным атрибутам
					if (is_numeric($record)) {
						$model_obj = $model_obj->findOne($record);
                    } else {
                        $model_obj = $model_obj->find()->where($record_search)->one();
                    }

					// значение primary key
					$id_pk = '';

					// если нашли то запомнили pk
					if (!empty($model_obj)) {
						$id_pk = $model_obj->primaryKey;
                    } else {
						$class = "common\models\\".$model_name;
						$model_obj = new $class;
					}

					// если нашли и её нужно создать создаем
					if (empty($id_pk) && $relation['added']) {
						$model_obj->attributes = $record;
						if ($model_obj->save()) {
							$id_pk = $model_obj->primaryKey;
                        }
					}

					// если не получилось создать и не нашли выходим
					if (empty($id_pk)) {
						continue;
                    }

					// добавляем к данным pk + ход конем когда таблица связывается на саму себя
					/*if (!isset($insert[$model_pk_field])) {
						$insert[$model_pk_field] = $id_pk;
					}*/

					// записываем поля из POST для dbl таблицы, если такие имеются
					if (isset($relation['fields_dbl'])) {
						foreach ($relation['fields_dbl'] as $field) {
							$insert[$field] = $record[$field];
                        }
                    }

					// если есть что добавить, например тип
					/*if (isset($relation['insertValues'])) {
						$insert = array_merge($insert,$relation['insertValues']);
					}*/

					// получаем названия полей для запроса
					//$fields = array_keys($insert);

					$this->owner->link($relation_name,$model_obj,$insert);
					// вставляем
					/*$sql = "INSERT INTO $dbl_table (".implode(',',$fields).")
								VALUES ('".implode("','",$insert)."')";
					Yii::$app->db->createCommand($sql)->execute();*/
				}
			} else {
				// primary key ids
				$pk_ids = [0];
				$pkr_field = $model_obj->primaryKey()[0];

				foreach ($POST as $record)
				{
					if (isset($relation['insertValues']))
						$record = array_merge($record,$relation['insertValues']);

                    if (!empty($record[$pkr_field]))
                    {
                    	$model = $model_obj::findOne($record[$pkr_field]);

                    	if (empty($model))
                    		$model = $model_obj;
                    }
                    elseif(!isset($record[$pkr_field]))
                    {
                    	$record[$pk_field] = $pk_value;
                    	// пытаемся найти по введеным данным
						$model = $model_obj->find()->filterWhere($record)->one();
                    }

					if (!empty($model))
					{
						$model->attributes = $record;
						$pk_ids[] = $model->primaryKey;
					}
                    else
                    {
						if (!empty($relation['added']))
						{
							// если не нашли и её нужно создать создаем
							$class = "common\models\\".$model_name;
							$model = new $class;
							$model->attributes = $record;
							$model->$pk_field = $pk_value;
						}
                    }

                    if ($model->save())
						$pk_ids[] = $model->primaryKey;

					$model = null;
				}

				$where = '';

				if (isset($relation['insertValues'])) {
					foreach ($relation['insertValues'] as $field=>$value) {
						$where .= "AND $field = '$value' ";
                    }
                }

				// удалям все непривязанное
				$sql = "DELETE FROM $table
							WHERE
								$pk_field = $pk_value
							AND $model_pk_field NOT IN (".implode(',', $pk_ids).")
							 $where";
				Yii::$app->db->createCommand($sql)->execute();
			}
		}
	}

    /**
     * Удаляет связь
     *
     * @param $relation
     */
    public function deleteRelationBehavior($relation)
	{
		unset($this->relations[$relation]);
	}
}

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

class Yorder extends Behavior
{
	// Имя таблицы где хранится сортировка
	public $jtable;

	// поле сортировки
	public $ord_field;

	public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
        ];
    }

	public function afterSave($event)
	{
		$pk_field = $this->owner->primaryKey()[0];
		$pk_value = $this->owner->primaryKey;
		//$relations 		= $this->getOwner()->relations();

		if (!isset($_POST))
			return;

		foreach ($this->relations as $relation_name=>$relation)
		{
			// получаем имя модели
			$model_name = $relation['modelname'];

			$POST = $this->getPOST($model_name,$relation_name);

			// если с POST к нам ничего не пришло выходим
			if ($POST===false)
				continue;

			// создаем объект класса
			$class = "app\models\\".$model_name;
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
				foreach ($POST as $record)
				{
					// устанавливаем ключ
					$insert = [];

					// удаляем поля из POST которые относятся к dbl таблице
					$record_search = $record;
					if (isset($relation['fields_dbl']))
						foreach ($relation['fields_dbl'] as $field)
							unset($record_search[$field]);

					// ищем по введеным атрибутам
					$model_obj = $model_obj->find()->where($record_search)->one();

					// значение primary key
					$id_pk = '';

					// если нашли то запомнили pk
					if (!empty($model_obj))
						$id_pk = $model_obj->primaryKey;
					else
					{
						$class = "app\models\\".$model_name;
						$model_obj = new $class;
					}

					// если нашли и её нужно создать создаем
					if (empty($id_pk) && $relation['added'])
					{
						$model_obj->attributes = $record;
						if ($model_obj->save())
							$id_pk = $model_obj->primaryKey;
					}

					// если не получилось создать и не нашли выходим
					if (empty($id_pk))
						continue;

					// добавляем к данным pk + ход конем когда таблица связывается на саму себя
					/*if (!isset($insert[$model_pk_field]))
						$insert[$model_pk_field] = $id_pk;*/

					// записываем поля из POST для dbl таблицы, если такие имеются
					if (isset($relation['fields_dbl']))
						foreach ($relation['fields_dbl'] as $field)
							$insert[$field] = $record[$field];

					// если есть что добавить, например тип
					/*if (isset($relation['insertValues']))
						$insert = array_merge($insert,$relation['insertValues']);*/

					// получаем названия полей для запроса
					//$fields = array_keys($insert);

					$this->owner->link($relation_name,$model_obj,$insert);
					// вставляем
					/*$sql = "INSERT INTO $dbl_table (".implode(',',$fields).")
								VALUES ('".implode("','",$insert)."')";
					Yii::$app->db->createCommand($sql)->execute();*/
				}
			}
			else
			{
				// primary key ids
				$pk_ids = [0];

				foreach ($POST as $record)
				{
					$record[$pk_field] = $pk_value;

					if (isset($relation['insertValues']))
						$record = array_merge($record,$relation['insertValues']);

					// пытаемся найти по введеным данным
					$model = $model_obj->find()->where($record)->one();

					if (!empty($model))
						$pk_ids[] = $model->primaryKey;
					else
						if (!empty($relation['added']))
						{
							// если не нашли и её нужно создать создаем
							$class = "app\models\\".$model_name;
							$model = new $class;
							$model->attributes = $record;

							if ($model->save())
								$pk_ids[] = $model->primaryKey;
						}
				}

				$where = '';

				if (isset($relation['insertValues']))
					foreach ($relation['insertValues'] as $field=>$value)
						$where .= "AND $field = '$value' ";

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

	// Удаляет связь
	public function deleteRelationBehavior($relation)
	{
		unset($this->relations[$relation]);
	}
}
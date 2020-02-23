<?php
/**
    Класс модель для настройки копирования форм и коллекций
*/
namespace backend\models\forms;

use common\models\Form;
use common\models\FormRow;
use common\models\FormElement;
use common\models\FormInput;

use common\models\Collection;
use common\models\CollectionRecord;
use common\models\CollectionColumn;
use Yii;
use yii\base\Model;

class FormCopy extends Model
{
    public $copydata;

    public function rules()
    {
        return [
            [['copydata'], 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'copydata' => 'Копировать данные',
        ];
    }

    public function сopyForm($form)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try
        {
            $copyForm = new Form;
            $copyForm->attributes = $form->attributes;
            $copyForm->id_collection = null;
            $copyForm->name = 'Копия - '.$form->name;

            $oldToNewInputs = $visibleInputs = [];

            if ($copyForm->save())
            {
                $collection = new Collection;
                $collection->id_form = $copyForm->id_form;
                $collection->name = $copyForm->name;
                $collection->save();

                $copyForm->id_collection = $collection->id_collection;
                $copyForm->updateAttributes(['id_collection']);

                foreach ($form->rows as $rkey => $row)
                {
                    $newRow = new FormRow;
                    $newRow->attributes = $row->attributes;
                    $newRow->id_form = $copyForm->id_form;
                    $newRow->ord = $row->ord;

                    if ($newRow->save())
                    {
                        foreach ($row->elements as $key => $element)
                        {
                            $copyElement = new FormElement;
                            $copyElement->attributes = $element->attributes;
                            $copyElement->id_row = $newRow->id_row;

                            if (!empty($element->input))
                            {
                                $newInput = new FormInput;
                                $newInput->attributes = $element->input->attributes;
                                $newInput->id_form = $copyForm->id_form;

                                if (!$newInput->save())
                                    print_r($newInput->errors);

                                $copyElement->id_input = $newInput->id_input;

                                $column = new CollectionColumn;
                                $column->name = $newInput->name;
                                $column->alias = $newInput->fieldname;
                                $column->id_collection = $copyForm->id_collection;
                                $column->type = $newInput->type;

                                if (!$column->save())
                                    print_r($column->errors);

                                $newInput->id_column = $column->id_column;
                                $newInput->updateAttributes(['id_column']);

                                $oldToNewInputs[$element->input->id_input] = $newInput->id_input;
                            }

                            if ($copyElement->save())
                            {
                                if (!empty($element->visibleInputs))
                                {
                                    foreach ($element->visibleInputs as $vikey => $vinput)
                                        $visibleInputs[$copyElement->id_element][$vinput->id_input_visible] = $vinput->values;
                                }
                            }

                            if (!empty($element->subForm))
                                self::assignForm($element->id_form,'',$copyForm,'',$copyElement);
                        }
                    }
                    else
                    {
                        print_r($newRow->errors);
                    }
                }

                if (!empty($visibleInputs))
                {
                    foreach ($visibleInputs as $id_element => $inputs)
                    {
                        foreach ($inputs as $id_input => $values)
                        {
                            if (!empty($oldToNewInputs[$id_input]))
                                Yii::$app->db->createCommand()->insert('form_visibleinput',[
                                    'id_element'=>$id_element,
                                    'values'=>$values,
                                    'id_input_visible'=>$oldToNewInputs[$id_input],
                                ])->execute();
                        }
                    }
                }

                $transaction->commit();

                if ($this->copydata)
                {
                    $oldColumns = $form->collection->getColumns()->indexBy('id_column')->all();

                    foreach ($oldColumns as $key => $column)
                    {
                        if ($column->type == CollectionColumn::TYPE_CUSTOM)
                        {
                            $newColumn = new CollectionColumn;
                            $newColumn->attributes = $column->attributes;
                            $newColumn->id_collection = $copyForm->id_collection;
                            $newColumn->save();
                        }
                    }

                    $mongoCollection = Yii::$app->mongodb->getCollection('collection' . $copyForm->id_collection);

                    $query = $form->collection->getDataQuery();

                    $newColumns = $copyForm->collection->getColumns()->indexBy('alias')->all();

                    foreach ($query->all() as $key => $recordData)
                    {
                        $recordModel = new CollectionRecord;
                        $recordModel->id_collection = $copyForm->id_collection;

                        if ($recordModel->save())
                        {
                            $inserData = ['id_record'=>$recordModel->id_record];

                            foreach ($recordData as $rkey => $data)
                            {
                                $id_column = preg_replace('/\D/', '', $rkey);

                                $oldColumn = $oldColumns[$id_column]??false;

                                if (!empty($oldColumn))
                                    $inserData[str_replace($id_column, $newColumns[$oldColumn->alias]->id_column, $rkey)] = $data;
                            }

                            $mongoCollection->insert($inserData);
                        }
                        else
                            print_r($recordModel->errors);
                    }
                }

                return $copyForm;
            }
        }
        catch (Exception $e)
        {
            $transaction->rollBack();
            throw $e;

            return false;
        }

        return false;
    }

    public static function assignForm($id_form, $id_row, $parentForm, $prefix='', $element=null)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try
        {
            $copyForm = Form::findOne($id_form);

            $subForm = new Form;
            $subForm->is_template = 2;
            $subForm->id_collection = $parentForm->id_collection;
            $subForm->name = $parentForm->name.' '.$copyForm->name;

            if ($subForm->save())
            {
                if (empty($element))
                {
                    $newElement = new FormElement;
                    $newElement->id_row = $id_row;
                    $newElement->ord = Yii::$app->db->createCommand("SELECT count(*) FROM form_element WHERE id_row = $id_row")->queryScalar();
                }
                else
                    $newElement = $element;

                $newElement->id_form = $subForm->id_form;

                if ($newElement->save())
                {
                    foreach ($copyForm->rows as $key => $row)
                    {
                        $newRow = new FormRow;
                        $newRow->attributes = $row->attributes;
                        $newRow->id_form = $subForm->id_form;
                        $newRow->ord = $row->ord;

                        if ($newRow->save())
                        {
                            foreach ($row->elements as $key => $element)
                            {
                                $copyElement = new FormElement;
                                $copyElement->attributes = $element->attributes;
                                $copyElement->id_row = $newRow->id_row;

                                if (!empty($element->input))
                                {
                                    $newInput = new FormInput;
                                    $newInput->attributes = $element->input->attributes;
                                    $newInput->id_form = $parentForm->id_form;
                                    $newInput->fieldname = (!empty($prefix)?$prefix.'_':'').$newInput->fieldname;

                                    if (!$newInput->save())
                                    {
                                        $transaction->rollBack();
                                        print_r($newInput->errors);
                                    }

                                    $copyElement->id_input = $newInput->id_input;

                                    $column = new CollectionColumn;
                                    $column->name = $newInput->name;
                                    $column->alias = $newInput->fieldname;
                                    $column->id_collection = $parentForm->id_collection;
                                    $column->type = $newInput->type;

                                    if (!$column->save())
                                    {
                                        $transaction->rollBack();
                                        print_r($column->errors);
                                    }

                                    $newInput->id_column = $column->id_column;
                                    $newInput->updateAttributes(['id_column']);
                                }

                                $copyElement->save();
                            }
                        }
                        else
                        {
                            $transaction->rollBack();
                            print_r($newRow->errors);
                        }
                    }
                }
                else
                {
                    $transaction->rollBack();
                    print_r($newElement->errors);
                }
            }

            $transaction->commit();
        }
        catch (Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
    }
}

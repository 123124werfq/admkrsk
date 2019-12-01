<?php

namespace console\controllers;

use common\models\Collection;
use common\models\Form;
use common\models\FormRow;
use common\models\FormElement;
use common\models\FormInput;
use Yii;
use yii\console\Controller;

class CollectionController extends Controller
{
    public function actionIndex()
    {
        $collection = Collection::find()->where('id_form IS NULL')->all();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            foreach ($collection as $key => $collection)
            {
                $form = new Form;
                $form->id_collection = $collection->id_collection;
                $form->name = $collection->name;
                
                if ($form->save())
                {
                    foreach ($collection->columns as $ckey => $column)
                    {
                        $input = new FormInput;
                        $input->label = $input->name = $column->name;
                        $input->type = $column->type;
                        $input->id_form = $form->id_form;
                        $input->id_column = $column->id_column;

                        if (!$input->save())
                            print_r($input->errors);

                        $row = new FormRow;
                        $row->id_form = $form->id_form;
                        if (!$row->save())
                            print_r($row->errors);

                        $element = new FormElement;
                        $element->id_row = $row->id_row;
                        $element->id_input = $input->id_input;
                        if (!$element->save())
                            print_r($element->errors);
                    }

                    $collection->id_form = $form->id_form;
                    $collection->updateAttributes(['id_form']);
                }
            }

            $transaction->commit();

        } catch (\Exception $e) 
        {
            $transaction->rollBack();
        }

    }
}
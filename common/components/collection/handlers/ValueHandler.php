<?php

namespace components\collection\handlers;

class ValueHandler extends ValueHandler
{
    public $value;

    /**
     * @inheritdoc
     */
    public function load()
    {
        $valueModel = $this->getValueModel();

        return $valueModel->value;
    }

    /**
     * @inheritdoc
     */
    public function defaultValue()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function save()
    {
        /*$EavModel = $this->attributeHandler->owner;
        $valueModel = $this->getValueModel();
        $attribute = $this->attributeHandler->getAttributeName();

        if (isset($EavModel->attributes[$attribute])) {
            $valueModel->value = $EavModel->attributes[$attribute];
            if (!$valueModel->save()) {
                throw new \Exception("Can't save value model");
            }
        }*/
    }

    public function __toString()
    {
        return $this->value;
    }

    /*public function addRules()
    {
        $model = &$this->attributeHandler->owner;
        $attribute = &$this->attributeHandler->attributeModel;
        $attribute_name = $this->attributeHandler->getAttributeName();

        if ($attribute->eavType->storeType == ValueHandler::STORE_TYPE_RAW) {
            $model->addRule($attribute_name, 'default', ['value' => $attribute->defaultValue]);
        }
    }*/
}
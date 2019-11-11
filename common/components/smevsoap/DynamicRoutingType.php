<?php

namespace common\components\smevsoap;

class DynamicRoutingType
{

    /**
     * @var string[] $DynamicValue
     * @access public
     */
    public $DynamicValue = null;

    /**
     * @param string[] $DynamicValue
     * @access public
     */
    public function __construct($DynamicValue)
    {
      $this->DynamicValue = $DynamicValue;
    }

}

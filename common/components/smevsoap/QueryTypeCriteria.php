<?php

namespace common\components\smevsoap;

class QueryTypeCriteria
{

    /**
     * @var TypeCriteria $messageTypeCriteria
     * @access public
     */
    public $messageTypeCriteria = null;

    /**
     * @param TypeCriteria $messageTypeCriteria
     * @access public
     */
    public function __construct($messageTypeCriteria)
    {
      $this->messageTypeCriteria = $messageTypeCriteria;
    }

}

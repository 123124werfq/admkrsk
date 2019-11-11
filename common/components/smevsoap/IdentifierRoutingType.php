<?php

namespace common\components\smevsoap;

class IdentifierRoutingType
{

    /**
     * @var string[] $IdentifierValue
     * @access public
     */
    public $IdentifierValue = null;

    /**
     * @param string[] $IdentifierValue
     * @access public
     */
    public function __construct($IdentifierValue)
    {
      $this->IdentifierValue = $IdentifierValue;
    }

}

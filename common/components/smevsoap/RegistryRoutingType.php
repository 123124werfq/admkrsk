<?php

namespace common\components\smevsoap;

class RegistryRoutingType
{

    /**
     * @var RegistryRecordRoutingType[] $RegistryRecordRouting
     * @access public
     */
    public $RegistryRecordRouting = null;

    /**
     * @param RegistryRecordRoutingType[] $RegistryRecordRouting
     * @access public
     */
    public function __construct($RegistryRecordRouting)
    {
      $this->RegistryRecordRouting = $RegistryRecordRouting;
    }

}

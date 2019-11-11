<?php

namespace common\components\smevsoap;

class RegistryRecordRoutingType
{

    /**
     * @var int $RecordId
     * @access public
     */
    public $RecordId = null;

    /**
     * @var boolean $UseGeneralRouting
     * @access public
     */
    public $UseGeneralRouting = null;

    /**
     * @var DynamicRoutingType $DynamicRouting
     * @access public
     */
    public $DynamicRouting = null;

    /**
     * @var IdentifierRoutingType $IdentifierRouting
     * @access public
     */
    public $IdentifierRouting = null;

    /**
     * @param int $RecordId
     * @param boolean $UseGeneralRouting
     * @param DynamicRoutingType $DynamicRouting
     * @param IdentifierRoutingType $IdentifierRouting
     * @access public
     */
    public function __construct($RecordId, $UseGeneralRouting, $DynamicRouting, $IdentifierRouting)
    {
      $this->RecordId = $RecordId;
      $this->UseGeneralRouting = $UseGeneralRouting;
      $this->DynamicRouting = $DynamicRouting;
      $this->IdentifierRouting = $IdentifierRouting;
    }

}
